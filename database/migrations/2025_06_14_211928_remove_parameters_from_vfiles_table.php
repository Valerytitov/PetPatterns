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
            // Проверяем, существует ли колонка, чтобы избежать ошибок при повторном запуске
            if (Schema::hasColumn('vfiles', 'parameters')) {
                $table->dropColumn('parameters');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vfiles', function (Blueprint $table) {
            $table->json('parameters')->nullable()->after('price');
        });
    }
};
