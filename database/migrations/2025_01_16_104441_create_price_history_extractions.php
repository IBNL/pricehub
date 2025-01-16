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
        Schema::create('price_history_extractions', function (Blueprint $table) {
          $table->uuid('id');
          $table->foreignUuid('extraction_id')->nullable()->references('id')->on('extractions');
          $table->foreignUuid('product_id')->nullable()->references('id')->on('products');
          $table->date('reference_date');
          $table->decimal('price',10,2);
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_history_extractions');
    }
};
