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
    Schema::create('subcategories', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->foreignUuid('category_id')->nullable()->references('id')->on('categories');
      $table->string('name')->nullable();
      $table->string('slug')->nullable();
      $table->string('logo')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('subcategories');
  }
};
