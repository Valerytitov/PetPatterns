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
}
