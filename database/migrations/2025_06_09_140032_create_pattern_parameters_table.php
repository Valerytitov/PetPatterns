<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pattern_parameters', function (Blueprint $table) {
            $table->id(); // Поле для уникального номера (уже было)

            // Создаем связь с таблицей выкроек (Laravel сам поймет, что это таблица vfiles по названию vfile_id).
            // onDelete('cascade') означает, что при удалении основной выкройки все ее параметры удалятся автоматически. Это очень удобно.
            $table->foreignId('vfile_id')->constrained()->onDelete('cascade');

            // Техническое имя переменной, как в файле .val (например, "chest_girth")
            $table->string('variable_name');

            // Имя для отображения пользователю в форме (например, "Обхват груди, см")
            $table->string('display_name');

            // Необязательное поле для подсказки (например, "Измерять по самой широкой части")
            // ->nullable() означает, что это поле может быть пустым.
            $table->text('description')->nullable();

            $table->timestamps(); // Поля created_at и updated_at для отслеживания времени создания/обновления (уже были)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pattern_parameters');
    }
};
