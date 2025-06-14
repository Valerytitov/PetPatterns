<?php

namespace App\Models;

// PatternParameter больше не нужен, так как мы удаляем эту связь
// use App\Models\PatternParameter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vfile extends Model
{
    use HasFactory;

    // 1. Обновленный список полей, разрешенных для массового заполнения.
    //    Добавили нашу новую колонку 'parameters'.
    protected $fillable = [
        'slug', 'title', 'short', 'content', 'price',
        'val_file', 'vit_file', 'vit_data', 'image',
        'parameters',
    ];

    // 2. Указываем Laravel, что колонка 'parameters' - это массив (для авто-конвертации в/из JSON)
    protected $casts = [
        'parameters' => 'array',
        'vit_data' => 'array',
    ];

    // 3. Наш набор параметров по умолчанию, который будет автоматически добавляться к каждой новой выкройке
    private static $defaultParameters = [
        ['name' => 'ДС', 'description' => 'Длина спинки'],
        ['name' => 'ДИ', 'description' => 'Длина изделия'],
        ['name' => 'ОГ', 'description' => 'Обхват груди'],
        ['name' => 'ОТ', 'description' => 'Обхват талии'],
        ['name' => 'ОШ', 'description' => 'Обхват шеи'],
        ['name' => 'Мпл', 'description' => 'Расстояние между передними лапами'],
        ['name' => 'Дпл', 'description' => 'Длина передних лап'],
        ['name' => 'Дзл', 'description' => 'Длина задних лап'],
    ];

    /**
     * The "booted" method of the model.
     * Этот код выполняется автоматически при событиях модели.
     */
    protected static function booted()
    {
        // Событие 'creating' срабатывает ПЕРЕД сохранением новой выкройки в базу
        static::creating(function ($vfile) {
            // Если для выкройки еще не заданы параметры, заполняем их нашим набором по умолчанию.
            if (empty($vfile->parameters)) {
                $vfile->parameters = self::$defaultParameters;
            }
        });
    }

    // Статическая функция для генерации PDF. Теперь она использует наш локальный движок.
    public static function generatePDF($name, $destination, $vit_file, $val_file)
    {
        $enginePath = '/usr/local/bin/valentina';
        $outputDir = storage_path('app/pdf_output');

        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0775, true);
        }

        $platformArg = escapeshellarg('offscreen');
        $formatArg = 1; // PDF
        $vitArg = escapeshellarg($vit_file);
        $valArg = escapeshellarg($val_file);
        $basenameArg = escapeshellarg(pathinfo($val_file, PATHINFO_FILENAME));
        $destArg = escapeshellarg($outputDir);

        $command = sprintf(
            '%s -platform %s --format %d --basename %s --mfile %s --destination %s %s',
            $enginePath, $platformArg, $formatArg, $basenameArg, $vitArg, $destArg, $valArg
        );

        $shellOutput = shell_exec($command . ' 2>&1');

        $expectedPdfPath = $outputDir . '/' . pathinfo($val_file, PATHINFO_FILENAME) . '.pdf';

        if (!file_exists($expectedPdfPath)) {
            throw new \Exception("Не удалось сгенерировать PDF. Команда: " . $command . " | Вывод движка: " . $shellOutput);
        }

        return $expectedPdfPath;
    }

    // Метод generateCustomPDF остается без изменений, он использует новую версию generatePDF
    public function generateCustomPDF(array $measurements)
    {
        $originalVitPath = $this->vit_file;
        $tempVitPath = storage_path('app/vfiles/temp_' . uniqid() . '.vit');

        if (!copy($originalVitPath, $tempVitPath)) {
            throw new \Exception('Не удалось создать временный файл мерок.');
        }

        $vitXml = new \SimpleXMLElement(file_get_contents($tempVitPath));

        foreach ($measurements as $name => $value) {
            $incrementNode = $vitXml->xpath("//increment[@name='{$name}']");
            if (isset($incrementNode[0])) {
                $decodedValue = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                
                // Если значение является ссылкой на другое измерение (начинается с @), оставляем его как строку
                if (str_starts_with($decodedValue, '@')) {
                    $incrementNode[0][0] = $decodedValue;
                } else {
                    // Для числовых значений очищаем от нечисловых символов и приводим к float
                    $cleanedValue = preg_replace('/[^0-9.]/', '', $decodedValue);
                    $incrementNode[0][0] = (float)$cleanedValue;
                }
            }
        }

        // Удаляем отладочный вывод, чтобы увидеть содержимое и путь к .vit файлу
        // dd($vitXml->asXML(), $tempVitPath);

        $vitXml->asXML($tempVitPath);

        try {
            $pdfPath = self::generatePDF(
                uniqid('pattern_'),
                uniqid('dest_'),
                $tempVitPath,
                $this->val_file
            );
        } finally {
            if (file_exists($tempVitPath)) {
                unlink($tempVitPath);
            }
        }

        return $pdfPath;
    }
}
