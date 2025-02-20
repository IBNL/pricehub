<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class ExtractionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $extractionData = [
      [
        'id' => '0ae34de8-8c0e-11ef-b927-0242ac120002',
        'ecommerce_id' => '734c93d1-8b47-11ef-a076-0242ac120003',
        'extraction_type_id' => '29df6f23-52a0-11ef-86b9-0242ac120003',
        'subcategory_id' => '4eec0504-868e-11ef-830e-0242ac120002',
        'is_active' => '1',
        'settings' => '{"url": "https://www.terabyteshop.com.br/hardware/fontes", "store": {"id": "734c93d1-8b47-11ef-a076-0242ac120003", "name": "terabyteshop", "filesystem": {"name": "fs"}, "browser_provider": {"name": "puppeteer", "options": {"headless": false}}, "html_parse_provider": {"name": "cheerio"}}}',
        'created_at' => '2024-10-16 22:29:01',
      ],
      [
        'id' => '0ccaf743-872a-11ef-ab2a-0242ac120003',
        'ecommerce_id' => '14f96b57-5517-11ef-a7cc-0242ac120002',
        'extraction_type_id' => '29df6f23-52a0-11ef-86b9-0242ac120003',
        'subcategory_id' => '4eec0504-868e-11ef-830e-0242ac120002',
        'is_active' => '1',
        'settings' => '{"url": "https://www.pichau.com.br/hardware/fonte?sort=price-asc&page={}", "store": {"id": "14f96b57-5517-11ef-a7cc-0242ac120002", "name": "pichau", "filesystem": {"name": "fs"}, "browser_provider": {"name": "puppeteer", "options": {"headless": false}}, "html_parse_provider": {"name": "cheerio"}}}',
        'created_at' => '2024-10-10 17:06:54',
      ],
      [
        'id' => '199eddc9-868f-11ef-830e-0242ac120002',
        'ecommerce_id' => '564dcda8-52a0-11ef-86b9-0242ac120003',
        'extraction_type_id' => '29df6f23-52a0-11ef-86b9-0242ac120003',
        'subcategory_id' => 'fc6f5d90-868e-11ef-830e-0242ac120002',
        'is_active' => '1',
        'settings' => '{"url": "https://servicespub.prod.api.aws.grupokabum.com.br/catalog/v2/products-by-category/hardware/placas-mae?page_number={}&page_size=100&facet_filters=eyJrYWJ1bV9wcm9kdWN0IjpbInRydWUiXX0%3D&sort=price", "store": {"id": "564dcda8-52a0-11ef-86b9-0242ac120003", "name": "kabum", "filesystem": {"name": "fs"}, "browser_provider": {"name": "puppeteer", "options": {"headless": false}}, "html_parse_provider": {"name": "cheerio"}}}',
        'created_at' => '2024-10-15 22:51:53',
      ],
      [
        'id' => '99803007-52a0-11ef-86b9-0242ac120003',
        'ecommerce_id' => '564dcda8-52a0-11ef-86b9-0242ac120003',
        'extraction_type_id' => '29df6f23-52a0-11ef-86b9-0242ac120003',
        'subcategory_id' => '0f1ff8d3-52a0-11ef-86b9-0242ac120003',
        'is_active' => '1',
        'settings' => '{"url": "https://servicespub.prod.api.aws.grupokabum.com.br/catalog/v2/products-by-category/hardware/memoria-ram?page_number={}&page_size=100&facet_filters=eyJrYWJ1bV9wcm9kdWN0IjpbInRydWUiXX0%3D&sort=price", "store": {"id": "564dcda8-52a0-11ef-86b9-0242ac120003", "name": "kabum", "filesystem": {"name": "fs"}, "browser_provider": {"name": "puppeteer", "options": {"headless": false}}, "html_parse_provider": {"name": "cheerio"}}}',
        'created_at' => '2024-08-04 20:31:59',
      ]
    ];

    DB::transaction(function () use ($extractionData) {
      DB::table('extractions')->insert($extractionData);
    });
  }
}
