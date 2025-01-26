<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    $this->call(EcommerceSeeder::class);
    $this->call(ExtractionTypesSeeder::class);
    $this->call(CategoriesSeeder::class);    
    $this->call(SubcategoriesSeeder::class);
    $this->call(ExtractionSeeder::class);
  }
}
