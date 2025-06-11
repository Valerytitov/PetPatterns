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
        Schema::create('vfiles', function (Blueprint $table) {
            $table->id();
			$table->string('slug');
			$table->string('title');
			$table->longtext('content');
			$table->decimal('price', 10, 2);
			$table->string('val_file')->nullable();
			$table->string('vit_file')->nullable();
			$table->text('vit_data')->nullable();
			$table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vfiles');
    }
};
