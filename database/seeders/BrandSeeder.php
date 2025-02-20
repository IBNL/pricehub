<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $brandData = [
      [
        'id' => '272dd7e1-0328-4fcc-a058-69fec2a18734',
        'name' => 'samsung',
        'logo' => NULL,
        'created_at' => '2024-08-07 23:45:09',
      ],
      [
        'id' => '353a70f0-7bb0-4e7d-acac-80ffbbff4a4c',
        'name' => 'gigabyte',
        'logo' => NULL,
        'created_at' => '2024-08-04 20:30:06',
      ],
      [
        'id' => '5aae0ea3-5335-4fc4-82b3-017b407417c7',
        'name' => 'seagate',
        'logo' => NULL,
        'created_at' => '2024-10-15 22:47:26',
      ],
    ];

    DB::transaction(function () use ($brandData) {
      DB::table('brands')->insert($brandData);
    });
  }
}
