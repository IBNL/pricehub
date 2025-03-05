<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $productsData = [
      [
        'id' => '564dcda8-52a0-11ef-86b9-0242ac120003',
        'ecommerce_id' => '564dcda8-52a0-11ef-86b9-0242ac120003',
        'brand_id' => '5aae0ea3-5335-4fc4-82b3-017b407417c7',
        'subcategory_id' => '0f1ff8d3-52a0-11ef-86b9-0242ac120003',
        'is_active' => true,
        'url' => 'https://www.ecommerce.com.br/produto/520990',
        'name' => 'memória ram redragon, 8gb, 3200mhz, ddr4, cl16, vermelho - gm-701',
        'slug' => 'memoria-ram-redragon-8gb-3200mhz-ddr4-cl16-vermelho-gm-701',
        'last_date_price' => '2024-08-06',
        'last_price' => 145.99,
        'logo_from_ecommerce' => '',
        'logo' => '',
        'available' => true,
        'created_at' => '2024-08-04 20:27:33'
      ],
      [
        'id' => '0031f304-5cb7-46c9-b238-7fd82a9d7b56',
        'ecommerce_id' => '564dcda8-52a0-11ef-86b9-0242ac120003',
        'brand_id' => '5aae0ea3-5335-4fc4-82b3-017b407417c7',
        'subcategory_id' => '0f1ff8d3-52a0-11ef-86b9-0242ac120003',
        'is_active' => true,
        'url' => 'https://www.ecommerce.com.br/memoria-mancer-vant-8gb-1x8gb-ddr4-2666mhz-c19-preto-mcr-vnt2666-8gb',
        'name' => 'memoria mancer vant, 8gb (1x8gb), ddr4, 2666mhz, c19, preto, mcr-vnt2666-8gb',
        'slug' => 'memoria-mancer-vant-8gb-1x8gb-ddr4-2666mhz-c19-preto-mcr-vnt2666-8gb',
        'last_date_price' => null,
        'last_price' => null,
        'logo_from_ecommerce' => 'https://images.ecommerce.com.br/produtos/fotos/520990/memoria-redragon-8gb-3200mhz-ddr4-cl16-vermelho-gm-701_1708371448_original.jpg',
        'logo' => 'https://images.ecommerce.com.br/produtos/520990',
        'available' => true,
        'created_at' => '2024-08-04 20:27:33'
      ],
      [
        'id' => '0082ac9f-1c0d-41f0-97f8-af277788e381',
        'ecommerce_id' => '564dcda8-52a0-11ef-86b9-0242ac120003',
        'brand_id' => '5aae0ea3-5335-4fc4-82b3-017b407417c7',
        'subcategory_id' => '3e9e9383-537d-11ef-9847-0242ac120003',
        'is_active' => true,
        'url' => 'https://www.ecommerce.com.br/produto/12',
        'name' => 'processador intel core i9-11900k 11ª geração, 3.5 ghz (5.1ghz turbo), cache 16mb, octa core, lga1200, vídeo integrado - bx8070811900k',
        'slug' => 'processador-intel-core-i9-11900k-11a-geracao-35-ghz-51ghz-turbo-cache-16mb-octa-core-lga1200-video-integrado-bx8070811900k',
        'last_date_price' => null,
        'last_price' => null,
        'logo_from_ecommerce' => null,
        'logo' => null,
        'available' => false,
        'created_at' => '2024-08-04 20:27:33'
      ],
    ];

    DB::transaction(function () use ($productsData) {
      DB::table('products')->insert($productsData);
    });

  }
}
