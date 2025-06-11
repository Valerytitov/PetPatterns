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
        Schema::table('vfiles', function (Blueprint $table) {
            // Добавляем нашу новую колонку 'short' типа TEXT
            // Мы также указываем, что она должна идти после колонки 'title' для порядка
            $table->text('short')->after('title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vfiles', function (Blueprint $table) {
            // Здесь мы описываем, как отменить наше изменение (просто удалить колонку)
            $table->dropColumn('short');
        });
    }
};
