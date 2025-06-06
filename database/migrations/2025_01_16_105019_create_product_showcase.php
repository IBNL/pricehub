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
    Schema::create('product_showcase', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->foreignUuid('product_id')->nullable()->references('id')->on('products');
      $table->string('title')->nullable();
      $table->integer('sort')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('product_showcase');
  }
};
