<?php

namespace App\Services;

use App\Models\Vfile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ValentinaService
{
    /**
     * Главный метод для генерации PDF.
     * Он подготавливает файлы и запускает двухшаговый процесс.
     *
     * @param Vfile $vfile Модель выкройки
     * @param array $measurements Массив с мерками от пользователя
     * @return string Абсолютный путь к готовому PDF
     */
    public function generatePdf(Vfile $vfile, array $measurements): string
    {
        // --- Подготовка ---
        // Создаем временную папку для этой сессии генерации
        $runDirectory = storage_path('app/valentina_run_' . uniqid());
        File::ensureDirectoryExists($runDirectory);

        // --- Шаг 1: Копируем .val во временную папку, чтобы избежать проблем с правами ---
        $originalValContent = Storage::disk('public')->get($vfile->val_file);
        // Очищаем от тегов, которые могут помешать в CLI-режиме
        $cleanedValContent = preg_replace('/<watermark[^>]*>.*?<\/watermark>/s', '', $originalValContent);
        // Также удаляем старую ссылку на файл мерок, т.к. мы передаем его через --mfile
        $cleanedValContent = preg_replace('/<measurements[^>]*\/>/s', '', $cleanedValContent);
        $valFilePath = $runDirectory . '/pattern.val';
        File::put($valFilePath, $cleanedValContent);

        // --- Шаг 2: Подготовка файла мерок .vit на основе шаблона ---
        $vitFilePath = $this->prepareVitFile($vfile, $measurements, $runDirectory);

        // --- Шаг 3: Запуск двухшаговой генерации PDF ---
        $outputFilename = $vfile->slug . '_pattern';
        
        $pdfPath = $this->runGenerationProcess($valFilePath, $vitFilePath, $runDirectory, $outputFilename);
        
        // --- Очистка ---
        // Удаляем временную папку со всеми файлами (.vit, _layout.pdf)
        File::deleteDirectory($runDirectory);
        
        return $pdfPath;
    }

    /**
     * Создает временный .vit файл на основе мерок.
     *
     * @param array $measurements
     * @param string $directory
     * @return string
     */
    private function prepareVitFile(Vfile $vfile, array $measurements, string $directory): string
    {
        // Берем за основу оригинальный .vit файл выкройки
        $originalVitContent = Storage::disk('public')->get($vfile->vit_file);
        $modifiedVitContent = $originalVitContent;

        // Заменяем значения в тегах <calculation> на пользовательские
        foreach ($measurements as $key => $value) {
            $pattern = '/(<increment name="' . preg_quote($key, '/') . '".*?<calculation>)(.*?)(<\/calculation>.*?<\/increment>)/s';
            $replacement = '$1' . floatval($value) . '$3';
            $modifiedVitContent = preg_replace($pattern, $replacement, $modifiedVitContent);
        }

        $vitPath = $directory . '/measurements.vit';
        File::put($vitPath, $modifiedVitContent);
        return $vitPath;
    }

    /**
     * Выполняет двухшаговый процесс: valentina (layout) -> pdfposter (tiling).
     * ФИНАЛЬНАЯ ВЕРСИЯ
     */
    private function runGenerationProcess(string $valFilePath, string $vitFilePath, string $outputDirectory, string $outputFilename): string
    {
        // Путь к финальному нарезанному PDF
        $finalTiledPdf = storage_path('app/public/generated/' . $outputFilename . '.pdf');
        File::ensureDirectoryExists(dirname($finalTiledPdf));

        // Запускаем Valentina через виртуальный экран
        $valentinaProcess = new Process([
            'xvfb-run', '--auto-servernum',
            'valentina',
            '--platform', 'offscreen',
            '-f', '1',
            '-m', $vitFilePath,
            '-d', $outputDirectory,
            '-b', $outputFilename, // Используем чистое имя, valentina сама добавит суффиксы
            $valFilePath,
        ]);
        $valentinaProcess->setTimeout(300);
        $valentinaProcess->run();

        // Проверяем, что процесс завершился без кода ошибки
        if (!$valentinaProcess->isSuccessful()) {
            throw new ProcessFailedException($valentinaProcess);
        }

        // --- НАДЕЖНЫЙ ПОИСК РЕЗУЛЬТАТА ---
        $pdfFileFound = '';
        $filesInRunDirectory = File::files($outputDirectory);
        foreach ($filesInRunDirectory as $file) {
            // Ищем первый попавшийся PDF файл в папке
            if (str_ends_with(strtolower($file->getFilename()), '.pdf')) {
                $pdfFileFound = $file->getPathname();
                break;
            }
        }

        // Если после успешного выполнения valentina мы не нашли PDF - это ошибка
        if (empty($pdfFileFound)) {
            throw new \Exception('Valentina process completed but did not create any PDF file.');
        }
        // ------------------------------------

        // Запускаем pdfposter с НАЙДЕННЫМ файлом
        $pdfposterProcess = new Process([
            'pdfposter',
            '-s1',
            $pdfFileFound, // <-- Используем путь к реально существующему файлу
            $finalTiledPdf,
        ]);
        $pdfposterProcess->setTimeout(180);
        $pdfposterProcess->mustRun();
        
        return $finalTiledPdf;
    }
}