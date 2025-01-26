<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class ExtractionTypesSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $extractionTypesData = [
      [
        'id' => '29df6f23-52a0-11ef-86b9-0242ac120003',
        'name' => 'subcategoria',
        'created_at' => '2024-08-04 20:28:52',
      ]
    ];

    DB::transaction(function () use ($extractionTypesData) {
      DB::table('extraction_types')->insert($extractionTypesData);
    });
  }
}
