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
            $table->dropColumn('vit_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vfiles', function (Blueprint $table) {
            // В случае отката миграции, добавляем колонку обратно, nullable, так как старых данных может не быть
            $table->json('vit_data')->nullable()->after('vit_file');
        });
    }
};
