<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $categoriesData = [
      'id' => 'facbe0b8-529f-11ef-86b9-0242ac120003',
      'name' => 'hardware',
      'logo' => NULL,
      'created_at' => '2024-08-04 20:27:33'
    ];

    DB::transaction(function () use ($categoriesData) {
      DB::table('categories')->insert($categoriesData);
    });

  }
}
