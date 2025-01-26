<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class SubcategoriesSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $subcategories = [
      [
        'id' => '0f1ff8d3-52a0-11ef-86b9-0242ac120003',
        'category_id' => 'facbe0b8-529f-11ef-86b9-0242ac120003',
        'name' => 'memória ram',
        'slug' => 'memoria-ram',
        'logo' => NULL,
        'created_at' => '2024-08-04 20:28:07',
      ],
      [
        'id' => '10abf99c-8430-11ef-8a65-0242ac120002',
        'category_id' => 'facbe0b8-529f-11ef-86b9-0242ac120003',
        'name' => 'coolers',
        'slug' => 'coolers',
        'logo' => NULL,
        'created_at' => '2024-10-06 22:12:24',
      ],
      [
        'id' => '20a57804-8432-11ef-8a65-0242ac120002',
        'category_id' => 'facbe0b8-529f-11ef-86b9-0242ac120003',
        'name' => 'disco rigido hd',
        'slug' => 'disco-rigido-hd',
        'logo' => NULL,
        'created_at' => '2024-10-06 22:27:10',
      ],
      [
        'id' => '3e9e9383-537d-11ef-9847-0242ac120003',
        'category_id' => 'facbe0b8-529f-11ef-86b9-0242ac120003',
        'name' => 'processadores',
        'slug' => 'processadores',
        'logo' => NULL,
        'created_at' => '2024-08-05 22:51:25',
      ],
      [
        'id' => '4eec0504-868e-11ef-830e-0242ac120002',
        'category_id' => 'facbe0b8-529f-11ef-86b9-0242ac120003',
        'name' => 'fontes',
        'slug' => 'fontes',
        'logo' => NULL,
        'created_at' => '2024-10-09 22:32:03',
      ],
      [
        'id' => '51bd9f53-868f-11ef-830e-0242ac120002',
        'category_id' => 'facbe0b8-529f-11ef-86b9-0242ac120003',
        'name' => 'ssd',
        'slug' => 'ssd',
        'logo' => NULL,
        'created_at' => '2024-10-09 22:39:18',
      ],
      [
        'id' => 'fc6f5d90-868e-11ef-830e-0242ac120002',
        'category_id' => 'facbe0b8-529f-11ef-86b9-0242ac120003',
        'name' => 'placa mãe',
        'slug' => 'placa-mae',
        'logo' => NULL,
        'created_at' => '2024-10-09 22:36:55',
      ],
      [
        'id' => 'ff1c56e6-537b-11ef-9847-0242ac120003',
        'category_id' => 'facbe0b8-529f-11ef-86b9-0242ac120003',
        'name' => 'placa de vídeo',
        'slug' => 'placa-de-video',
        'logo' => NULL,
        'created_at' => '2024-08-05 22:42:29',
      ],
    ];

    DB::transaction(function () use ($subcategories) {
      DB::table('subcategories')->insert($subcategories);
    });
  }
}
