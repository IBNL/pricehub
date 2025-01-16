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
        Schema::create('daily_extractions', function (Blueprint $table) {
          $table->uuid('id');
          $table->foreignUuid('extraction_id')->nullable()->references('id')->on('extractions');
          $table->json('input')->nullable();
          $table->json('output')->nullable();
          $table->boolean('extraction_success')->default(0);
          $table->date('reference_date');
          $table->boolean('send_to_process')->default(0);
          $table->timestamps();
          $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_extractions');
    }
};
