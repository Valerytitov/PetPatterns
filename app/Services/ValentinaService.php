<?php

namespace App\Services;

use App\Models\Vfile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ValentinaService
{
    protected string $valentinaExecutable;
    protected string $outputDir;

    public function __construct()
    {
        $this->valentinaExecutable = '/usr/local/bin/valentina';
        $this->outputDir = Storage::disk('public')->path('generated_pdfs');

        // Убедимся, что папка для вывода существует
        if (!is_dir($this->outputDir)) {
            mkdir($this->outputDir, 0775, true);
        }
    }

    /**
     * @param Vfile $vfile Модель выкройки
     * @param array $userMeasurements Массив мерок от пользователя
     * @return string|null Возвращает путь к сгенерированному PDF или null в случае ошибки
     */
    public function generatePdf(Vfile $vfile, array $userMeasurements): ?string
    {
        set_time_limit(180);

        $tempDirName = 'temp-run-' . Str::uuid();
        Storage::disk('public')->makeDirectory($tempDirName);
        $tempDirPath = Storage::disk('public')->path($tempDirName);
        Log::info("Created temporary directory: {$tempDirPath}");

        try {
            // Модифицируем и сохраняем .vit файл
            $tempVitPath = $this->createModifiedVitFile($vfile, $userMeasurements, $tempDirPath);
            
            // Копируем и модифицируем .val файл
            $tempValPath = $this->createModifiedValFile($vfile, $tempDirPath, basename($tempVitPath));

            // Формируем и выполняем команду
            $outputPdfBasename = 'pattern_' . $vfile->id . '_' . uniqid();
            $command = "{$this->valentinaExecutable} -platform offscreen --format 1 --basename \"{$outputPdfBasename}\" --mfile \"{$tempVitPath}\" --destination \"{$this->outputDir}\" \"{$tempValPath}\"";
            Log::info("Executing command: {$command}");
            
            shell_exec($command . ' 2>&1'); // Ошибки будут в логе Laravel, если они есть

            $outputPdfPath = "{$this->outputDir}/{$outputPdfBasename}.pdf";

            return file_exists($outputPdfPath) ? $outputPdfPath : null;

        } finally {
            // Гарантированно удаляем временную папку
            if (Storage::disk('public')->exists($tempDirName)) {
                Storage::disk('public')->deleteDirectory($tempDirName);
                Log::info("Cleaned up temporary directory: {$tempDirName}");
            }
        }
    }

    private function createModifiedVitFile(Vfile $vfile, array $userMeasurements, string $tempDirPath): string
    {
        $templateVitContent = Storage::disk('public')->get($vfile->vit_file);
        $modifiedVitContent = $templateVitContent;

        foreach ($userMeasurements as $name => $value) {
            $pattern = '/(<m\s+[^>]*?name="' . preg_quote($name, '/') . '"[^>]*?value=")([^"]*)("[^>]*?>)/i';
            $replacement = '${1}' . htmlspecialchars((string)$value, ENT_QUOTES) . '${3}';
            $modifiedVitContent = preg_replace($pattern, $replacement, $modifiedVitContent, 1);
        }
        
        $tempVitFileName = 'user_measures.vit';
        $tempVitPath = "{$tempDirPath}/{$tempVitFileName}";
        file_put_contents($tempVitPath, $modifiedVitContent);
        Log::info("Saved VIT file to: {$tempVitPath}");
        
        return $tempVitPath;
    }

    private function createModifiedValFile(Vfile $vfile, string $tempDirPath, string $vitFileName): string
    {
        $originalValPath = $vfile->val_file;
        $tempValFileName = basename($originalValPath);
        $tempValPath = "{$tempDirPath}/{$tempValFileName}";
        copy(Storage::disk('public')->path($originalValPath), $tempValPath);
        $valContent = file_get_contents($tempValPath);
        $valContent = preg_replace('/(<measurements)\s+path="[^"]*"\s*(\/>)/', '$1$2', $valContent);
        file_put_contents($tempValPath, $valContent);
        Log::info("Cleaned path attribute from temporary VAL file.");

        return $tempValPath;
    }
}