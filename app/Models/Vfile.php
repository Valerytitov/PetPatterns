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
    //    Удалили 'vit_data'.
    protected $fillable = [
        'slug', 'title', 'short', 'content', 'price',
        'val_file', 'vit_file', 'image',
    ];

    // 2. Указываем Laravel, что колонка 'parameters' - это массив (для авто-конвертации в/из JSON)
    //    Удалили 'vit_data'.
    protected $casts = [
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
            // if (empty($vfile->parameters)) {
            //     $vfile->parameters = self::$defaultParameters;
            // }
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

    // Метод generateCustomPDF теперь полностью генерирует .vit файл с нуля
    public function generateCustomPDF(array $measurements)
    {
        $tempVitPath = storage_path('app/public/temp/temp_measures_' . uniqid() . '.vit');

        $xmlString = '<measurements></measurements>';
        $vitXml = new \SimpleXMLElement($xmlString);

        foreach ($measurements as $name => $value) {
            $measurementNode = $vitXml->addChild('measurement');
            $measurementNode->addAttribute('name', $name);
            $measurementNode->addAttribute('unit', 'cm'); // Предполагаем, что единица измерения - cm

            $decodedValue = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');

            if (str_starts_with($decodedValue, '@')) {
                $measurementNode->addAttribute('value', $decodedValue);
            } else {
                $cleanedValue = preg_replace('/[^0-9.]/', '', $decodedValue);
                $measurementNode->addAttribute('value', (string)(float)$cleanedValue);
            }
        }

        $vitXml->asXML($tempVitPath);

        try {
            $pdfPath = self::generatePDF(
                uniqid('pattern_'), // $name - basename для PDF, будет создан на основе val_file
                uniqid('dest_'),   // $destination - каталог для PDF, будет использован outputDir
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
