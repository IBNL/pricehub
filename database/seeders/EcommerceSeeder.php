<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class EcommerceSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $ecommerceData = [
      [
        'id' => '14f96b57-5517-11ef-a7cc-0242ac120002',
        'url' => 'https://www.pichau.com.br',
        'name' => 'pichau',
        'logo' => NULL,
        'created_at' => '2024-08-07 23:45:09',
      ],
      [
        'id' => '564dcda8-52a0-11ef-86b9-0242ac120003',
        'url' => 'https://www.kabum.com.br',
        'name' => 'kabum',
        'logo' => NULL,
        'created_at' => '2024-08-04 20:30:06',
      ],
      [
        'id' => '734c93d1-8b47-11ef-a076-0242ac120003',
        'url' => 'https://www.terabyteshop.com.br',
        'name' => 'terabyteshop',
        'logo' => NULL,
        'created_at' => '2024-10-15 22:47:26',
      ],
    ];

    DB::transaction(function () use ($ecommerceData) {
      DB::table('ecommerce')->insert($ecommerceData);
    });
  }
}
