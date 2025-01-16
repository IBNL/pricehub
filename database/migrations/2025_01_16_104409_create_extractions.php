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
        Schema::create('extractions', function (Blueprint $table) {
          $table->uuid('id')->primary();
          $table->foreignUuid('ecommerce_id')->nullable()->references('id')->on('ecommerce');
          $table->foreignUuid('extraction_type_id')->nullable()->references('id')->on('extraction_types');
          $table->foreignUuid('subcategory_id')->nullable()->references('id')->on('subcategories');
          $table->boolean('is_active')->default(1);
          $table->json('settings');
          $table->timestamps();
          $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extractions');
    }
};
