<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Vfile; // Важно: импортируем модель выкройки

class PatternParameter extends Model
{
    use HasFactory;

    /**
     * Атрибуты, которые можно массово назначать (mass assignable).
     * Это защита Laravel, которая позволяет нам явно указать, какие поля
     * можно безопасно заполнять через методы вроде Model::create().
     *
     * @var array
     */
    protected $fillable = [
        'vfile_id',
        'variable_name',
        'display_name',
        'description',
    ];

    /**
     * Определяем обратную связь "один ко многим" (belongsTo):
     * Каждый "Параметр" (PatternParameter) принадлежит одной "Выкройке" (Vfile).
     * Это позволит нам легко получать доступ к данным выкройки из параметра, например: $parameter->vfile->name
     */
    public function vfile()
    {
        return $this->belongsTo(Vfile::class);
    }
}
