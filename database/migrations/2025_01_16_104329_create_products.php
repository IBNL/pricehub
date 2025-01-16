<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('products', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->foreignUuid('ecommerce_id')->nullable()->references('id')->on('ecommerce');
      $table->foreignUuid('brand_id')->nullable()->references('id')->on('brands');
      $table->foreignUuid('subcategory_id')->nullable()->references('id')->on('subcategories');
      $table->boolean('is_active')->default(1);
      $table->string('url')->nullable();
      $table->string('name')->nullable();
      $table->string('slug')->nullable();
      $table->date('last_date_price')->nullable();
      $table->decimal('last_price', 10, 2)->nullable();
      $table->string('logo_from_ecommerce')->nullable();
      $table->string('logo')->nullable();
      $table->boolean('available')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('products');
  }
};
