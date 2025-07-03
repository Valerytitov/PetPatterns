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
        \Log::info('Starting PDF generation', ['vfile_id' => $vfile->id]);
        // --- Подготовка ---
        $runDirectory = storage_path('app/valentina_run_' . uniqid());
        File::ensureDirectoryExists($runDirectory);
        \Log::info('Created temp directory', ['directory' => $runDirectory]);

        // --- Шаг 1: Копируем .val во временную папку ---
        $valFilePath = $this->prepareValFile($vfile, $runDirectory);
        \Log::info('Prepared VAL file', ['path' => $valFilePath]);

        // --- Шаг 2: Подготовка файла мерок .vit ---
        $vitFilePath = $this->prepareVitFile($vfile, $measurements, $runDirectory);
        \Log::info('Prepared VIT file', ['path' => $vitFilePath]);

        // --- Шаг 3: Запуск генерации ---
        $outputFilename = $vfile->slug . '_pattern';
        $tempPdfPath = $this->runGenerationProcess($valFilePath, $vitFilePath, $runDirectory, $outputFilename);

        // --- Копируем PDF в постоянную папку ---
        $permanentPdfPath = storage_path('app/public/generated/' . $outputFilename . '.pdf');
        File::ensureDirectoryExists(dirname($permanentPdfPath));
        File::copy($tempPdfPath, $permanentPdfPath);
        \Log::info('PDF copied to permanent location', ['from' => $tempPdfPath, 'to' => $permanentPdfPath]);

        // --- Очистка ---
        File::deleteDirectory($runDirectory);
        \Log::info('Cleaned up temp directory');

        return $permanentPdfPath;
    }

    private function prepareValFile(Vfile $vfile, string $runDirectory): string
    {
        $originalValContent = Storage::disk('public')->get($vfile->val_file);
        $cleanedValContent = preg_replace('/<watermark[^>]*>.*?<\/watermark>/s', '', $originalValContent);
        $cleanedValContent = preg_replace('/<measurements[^>]*\/>/s', '', $cleanedValContent);
        $valFilePath = $runDirectory . '/pattern.val';
        File::put($valFilePath, $cleanedValContent);
        return $valFilePath;
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
        \Log::info('runGenerationProcess started', [
            'val_file' => $valFilePath,
            'vit_file' => $vitFilePath,
            'output_dir' => $outputDirectory,
            'output_filename' => $outputFilename
        ]);

        $largeLayoutPdf = $outputDirectory . '/' . $outputFilename . '_1.pdf';
        $tiledPdf = $outputDirectory . '/' . $outputFilename . '_tiled.pdf';

        @unlink($outputDirectory . '/.pattern.val.lock');
        @chmod($outputDirectory, 0777);
        @chmod($valFilePath, 0666);
        @chmod($vitFilePath, 0666);
        if (file_exists($largeLayoutPdf)) {
            @chmod($largeLayoutPdf, 0666);
        }
        \Log::info('Set file permissions');

        // --- Valentina ---
        $valentinaProcess = new Process([
            'xvfb-run', '--auto-servernum',
            'valentina',
            '--platform', 'offscreen',
            '-f', '33',
            '--tiledPageformat', '4', // <- ИСПРАВЛЕНО: правильный флаг для формата плитки A4
            '-m', $vitFilePath,
            '-d', $outputDirectory,
            '-b', $outputFilename,
            '-u',
            '-l', 'cm',
            '-G', '0.5',
            $valFilePath,
        ]);
        $valentinaProcess->setTimeout(600);
        \Log::info('Starting Valentina process');
        $valentinaProcess->run();
        \Log::info('Valentina process finished');
        \Log::info('Valentina output', [
            'output' => $valentinaProcess->getOutput(),
            'error' => $valentinaProcess->getErrorOutput(),
            'exit_code' => $valentinaProcess->getExitCode()
        ]);

        $actualPdfPath = $largeLayoutPdf;
        if (!file_exists($actualPdfPath)) {
            $files = glob($outputDirectory . '/*');
            \Log::info('Files in output directory', [
                'directory' => $outputDirectory,
                'files' => $files,
                'expected' => $largeLayoutPdf,
                'actual' => $actualPdfPath
            ]);
            throw new \Exception('PDF файл не был создан. Valentina завершилась с кодом: ' . $valentinaProcess->getExitCode());
        }
        \Log::info('PDF file found successfully');

        // --- pdfposter временно отключён ---
        // $pdfPosterProcess = new Process([
        //     'pdfposter',
        //     '-s1',
        //     $actualPdfPath,
        //     $tiledPdf
        // ]);
        // $pdfPosterProcess->setTimeout(300);
        // $pdfPosterProcess->run();
        // if (!$pdfPosterProcess->isSuccessful()) {
        //     \Log::error('pdfposter failed', [
        //         'output' => $pdfPosterProcess->getOutput(),
        //         'error' => $pdfPosterProcess->getErrorOutput(),
        //     ]);
        //     return $actualPdfPath;
        // }
        // if (!file_exists($tiledPdf)) {
        //     throw new \Exception('pdfposter не создал итоговый PDF.');
        // }
        // \Log::info('runGenerationProcess completed with tiling', ['return_path' => $tiledPdf]);
        // return $tiledPdf;
        \Log::info('runGenerationProcess completed (tiled mode Valentina only)', ['return_path' => $actualPdfPath]);
        return $actualPdfPath;
    }
}