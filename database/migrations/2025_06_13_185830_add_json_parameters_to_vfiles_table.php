<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Удаляем старую таблицу, она нам больше не нужна
        Schema::dropIfExists('pattern_parameters');

        // Добавляем одну колонку типа JSON в таблицу vfiles
        Schema::table('vfiles', function (Blueprint $table) {
            $table->json('parameters')->nullable()->after('vit_data');
        });
    }

    public function down(): void
    {
        Schema::table('vfiles', function (Blueprint $table) {
            $table->dropColumn('parameters');
        });
    }
};
