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
     * ВЕРСИЯ С ОПТИМИЗАЦИЕЙ КОМПОНОВКИ И ОТСТУПОВ
     */
    private function runGenerationProcess(string $valFilePath, string $vitFilePath, string $outputDirectory, string $outputFilename): string
    {
        $largeLayoutPdf = $outputDirectory . '/' . $outputFilename . '.pdf';
        $finalTiledPdf = storage_path('app/public/generated/' . $outputFilename . '.pdf');
        File::ensureDirectoryExists(dirname($finalTiledPdf));

        // Удаляем только .pattern.val.lock до запуска Valentina
        @unlink($outputDirectory . '/.pattern.val.lock');

        // Устанавливаем права на временную папку и файлы
        @chmod($outputDirectory, 0777);
        @chmod($valFilePath, 0666);
        @chmod($vitFilePath, 0666);
        if (file_exists($largeLayoutPdf)) {
            @chmod($largeLayoutPdf, 0666);
        }

        $valentinaProcess = new Process([
            'xvfb-run', '--auto-servernum',
            'valentina',
            '--platform', 'offscreen',
            '-f', '1',
            '-m', $vitFilePath,
            '-d', $outputDirectory,
            '-b', basename($largeLayoutPdf, '.pdf'),
            '-u',
            '-l', 'cm',
            '-G', '0.5',
            $valFilePath,
        ]);
        $valentinaProcess->setTimeout(300);
        $valentinaProcess->run();
        
        \Log::info('Valentina output', [
            'output' => $valentinaProcess->getOutput(),
            'error' => $valentinaProcess->getErrorOutput(),
            'exit_code' => $valentinaProcess->getExitCode()
        ]);
        if (!$valentinaProcess->isSuccessful()) {
            throw new ProcessFailedException($valentinaProcess);
        }

        // Удаляем только .pattern.val.lock после запуска Valentina
        @unlink($outputDirectory . '/.pattern.val.lock');

        // Временно отключаем pdfposterProcess для отладки Valentina
        // $pdfposterProcess = new Process([
        //     'pdfposter',
        //     '-s1',
        //     $largeLayoutPdf,
        //     $finalTiledPdf,
        // ]);
        // $pdfposterProcess->setTimeout(180);
        // $pdfposterProcess->mustRun();
        
        return $largeLayoutPdf;
    }
}