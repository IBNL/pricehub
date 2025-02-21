<?php

namespace Tests\Feature\Core\UseCase\Product;

use App\Models\BrandModel;
use App\Models\DailyExtractionModel;
use App\Models\PriceHistoryExtractionModel;
use App\Models\ProductModel;
use App\Repositories\Eloquent\BrandEloquentRepository;
use App\Repositories\Eloquent\DailyExtractionEloquentRepository;
use App\Repositories\Eloquent\PriceHistoryExtractionEloquentRepository;
use App\Repositories\Eloquent\ProductEloquentRepository;
use Core\UseCase\Product\DTO\ProductProcessingInputDto;
use Core\UseCase\Product\ProductProcessingUseCase;
use DateTime;
use DB;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;


class ProductProcessingUseCaseTest extends TestCase
{

  protected $brandRepository;
  protected $productRepository;
  protected $priceHistoryExtractionRepository;
  protected $dailyExtractionRepository;

  protected function setUp(): void
  {
    parent::setUp();
    $seeders = [
      'EcommerceSeeder',
      'ExtractionTypesSeeder',
      'CategoriesSeeder',
      'SubcategoriesSeeder',
      'ExtractionSeeder',
    ];

    foreach ($seeders as $seeder) {
      Artisan::call('db:seed', ['--class' => $seeder]);
    }

    $this->brandRepository = new BrandEloquentRepository(new BrandModel());
    $this->productRepository = new ProductEloquentRepository(new ProductModel());
    $this->priceHistoryExtractionRepository = new PriceHistoryExtractionEloquentRepository(new PriceHistoryExtractionModel());
    $this->dailyExtractionRepository = new DailyExtractionEloquentRepository(new DailyExtractionModel());
  }


  #[DataProvider('dataProviderCreate')]
  public function test_create(
    array $data
  ): void {
    // arrange

    // criar uma brand quando necessario
    if ($data['brandsToInsertBatch']) {
      DB::transaction(function () use ($data) {
        DB::table('brands')->insert($data['brandsToInsertBatch']);
      });
    }


    // criar daily_extraction
    DB::transaction(function () use ($data) {
      DB::table('daily_extractions')->insert($data['dailyExtractionToInsertBatch']);
    });

    $productProcessingUseCase = new ProductProcessingUseCase(
      brandRepository: $this->brandRepository,
      productRepository: $this->productRepository,
      priceHistoryExtractionRepository: $this->priceHistoryExtractionRepository,
      dailyExtractionRepository: $this->dailyExtractionRepository
    );

    $input = file_get_contents($data['file_path']);

    // action
    $productProcessingUseCase->execute(
      input: new ProductProcessingInputDto(
        data: $input
      )
    );
   
    // assert
    $this->assertDatabaseCount('daily_extractions', $data['dailyExtractionsCount']);

    $this->assertDatabaseCount('brands', $data['brandsDatabaseCount']);


    foreach ($data['brandsNeedCreateData'] as $brandName) {
      $this->assertDatabaseHas('brands', ['name' => $brandName]);
    }

    $this->assertDatabaseCount('products', $data['productsNeedCreateCount']);

    foreach ($data['productsNeedCreateData'] as $productUrl) {
      $this->assertDatabaseHas('products', ['url' => $productUrl]);
    }

    $this->assertDatabaseCount('price_history_extractions', $data['priceHistoryExtractionsCount']);

    foreach ($data['priceHistoryExtractionPriceNeedCreateData'] as $priceHistoryExtractionData) {
      $this->assertDatabaseHas('price_history_extractions', [
        'price' => $priceHistoryExtractionData['price'],
        'reference_date' => $priceHistoryExtractionData['reference_date']
      ]);
    }

    $this->assertNotNull(DailyExtractionModel::first()->output);
    $this->assertJson(DailyExtractionModel::first()->output);
    $this->assertDatabaseHas('daily_extractions', [
      'extraction_success' => $data['extraction_success'],
      #'output' => $input
    ]);

    foreach ($data['productsNeedUpdateData'] as $productsNeedUpdateData) {
      $this->assertDatabaseHas('products', [
        'last_date_price' => $productsNeedUpdateData['last_date_price'],
        'last_price' => $productsNeedUpdateData['last_price'],
        'is_active' => $productsNeedUpdateData['is_active'],
        'available' => $productsNeedUpdateData['available'],
      ]);
    }

  }
  public static function dataProviderCreate(): array
  {
    return [
      'ecommerce: kabum | subcategory: memoria ram | brands: 3 | products: 4' => [
        'data' => [
          'file_path' => 'tests/files/kabum_memoria_ram_3_produtos.json',
          'brandsDatabaseCount' => 3,
          'brandsToInsertBatch' => [
            [
              'id' => '272dd7e1-0328-4fcc-a058-69fec2a18734',
              'name' => 'samsung',
              'logo' => NULL,
              'created_at' => '2024-08-07 23:45:09',
            ],
          ],
          'brandsNeedCreateData' => [
            'rise mode',
            'brazil pc'
          ],
          'productsNeedCreateCount' => 5,
          'productsNeedCreateData' => [
            'https://www.kabum.com.br/produto/102447',
            'https://www.kabum.com.br/produto/99428',
            'https://www.kabum.com.br/produto/551170',
            'https://www.kabum.com.br/produto/110937'
          ],
          'dailyExtractionToInsertBatch' => [
            [
              'id' => '066bbd1a-5902-4215-895c-3e57ef1b8319',
              'extraction_id' => '99803007-52a0-11ef-86b9-0242ac120003',
              'input' => '{"settings": "{"url": "https://servicespub.prod.api.aws.grupokabum.com.br/catalog/v2/products-by-category/hardware/memoria-ram?page_number={}&page_size=100&facet_filters=eyJrYWJ1bV9wcm9kdWN0IjpbInRydWUiXX0%3D&sort=price", "store": {"id": "564dcda8-52a0-11ef-86b9-0242ac120003", "name": "kabum", "filesystem": {"name": "fs"}, "browser_provider": {"name": "puppeteer", "options": {"headless": false}}, "html_parse_provider": {"name": "cheerio"}}}", "file_path": "src/Storage/99803007-52a0-11ef-86b9-0242ac120003/2024/8/20/", "ecommerce_id": "564dcda8-52a0-11ef-86b9-0242ac120003", "extraction_id": "99803007-52a0-11ef-86b9-0242ac120003", "reference_date": "2024-08-20", "subcategory_id": "0f1ff8d3-52a0-11ef-86b9-0242ac120003", "send_to_process": 1, "extraction_success": 0, "extraction_type_id": "29df6f23-52a0-11ef-86b9-0242ac120003", "extraction_type_name": "subcategoria"}',
              'extraction_success' => false,
              'reference_date' => '2024-08-20',
              'send_to_process' => true,
              'created_at' => '2024-08-20 20:31:59',
            ]
          ],
          'dailyExtractionsCount' => 1,
          'priceHistoryExtractionsCount' => 4,
          'priceHistoryExtractionPriceNeedCreateData' => [
            ['price' => 125, 'reference_date' => '2024-08-20 00:00:00'],
            ['price' => 49.99, 'reference_date' => '2024-08-20 00:00:00'],
            ['price' => 40, 'reference_date' => '2024-08-20 00:00:00'],
            ['price' => 69.99, 'reference_date' => '2024-08-20 00:00:00'],
          ],
          'extraction_success' => true,
          'productsNeedUpdateData' => [
            ['last_price' => 125, 'last_date_price' => '2024-08-20 00:00:00', 'is_active' => true, 'available' => true],
            ['last_price' => 49.99, 'last_date_price' => '2024-08-20 00:00:00', 'is_active' => true, 'available' => true],
            ['last_price' => 40, 'last_date_price' => '2024-08-20 00:00:00', 'is_active' => true, 'available' => true],
            ['last_price' => 69.99, 'last_date_price' => '2024-08-20 00:00:00', 'is_active' => true, 'available' => true],
          ],
        ],
      ],
      'ecommerce: kabum | subcategory: memoria ram | brands: 12 | products: 520' => [
        'data' => [
          'file_path' => 'tests/files/kabum_memoria_ram_520_produtos.json',
          'brandsDatabaseCount' => 12,
          'brandsToInsertBatch' => [],
          'brandsNeedCreateData' => ProductProcessingUseCaseTest::getBrandsFile520Products(file_path: 'tests/files/kabum_memoria_ram_520_produtos.json'),
          'productsNeedCreateCount' => 520,
          'productsNeedCreateData' => ProductProcessingUseCaseTest::getProductsFile520Products(file_path: 'tests/files/kabum_memoria_ram_520_produtos.json'),
          'dailyExtractionToInsertBatch' => [
            [
              'id' => '6d1d69a3-3ea0-450f-8e75-f53e270b6e0d',
              'extraction_id' => '99803007-52a0-11ef-86b9-0242ac120003',
              'input' => '{ "settings": "{"url": "https://servicespub.prod.api.aws.grupokabum.com.br/catalog/v2/products-by-category/hardware/memoria-ram?page_number={}&page_size=100&facet_filters=eyJrYWJ1bV9wcm9kdWN0IjpbInRydWUiXX0%3D&sort=price", "store": {"id": "564dcda8-52a0-11ef-86b9-0242ac120003", "name": "kabum", "filesystem": {"name": "fs"}, "browser_provider": {"name": "puppeteer", "options": {"headless": false}}, "html_parse_provider": {"name": "cheerio"}}}", "file_path": "src/Storage/99803007-52a0-11ef-86b9-0242ac120003/2024/9/1/", "ecommerce_id": "564dcda8-52a0-11ef-86b9-0242ac120003", "extraction_id": "99803007-52a0-11ef-86b9-0242ac120003", "reference_date": "2024-09-01", "subcategory_id": "0f1ff8d3-52a0-11ef-86b9-0242ac120003", "send_to_process": 1, "extraction_success": 0, "extraction_type_id": "29df6f23-52a0-11ef-86b9-0242ac120003", "extraction_type_name": "subcategoria" }',
              'extraction_success' => false,
              'reference_date' => '2024-09-01',
              'send_to_process' => true,
              'created_at' => '2024-09-01 20:31:59',
            ]
          ],
          'dailyExtractionsCount' => 1,
          'priceHistoryExtractionsCount' => 520,
          'priceHistoryExtractionPriceNeedCreateData' => ProductProcessingUseCaseTest::getPriceHistoryExtractionFile520Products(file_path: 'tests/files/kabum_memoria_ram_520_produtos.json'),
          'extraction_success' => true,
          'productsNeedUpdateData' => ProductProcessingUseCaseTest::getProductsUpdateFile520Products(file_path: 'tests/files/kabum_memoria_ram_520_produtos.json')
        ],
      ],
    ];
  }

  public static function getBrandsFile520Products(string $file_path): array
  {
    $input = file_get_contents($file_path);
    
    $data = json_decode($input);
    
    return collect($data->products)->pluck('brand')->unique()->values()->toArray();
  }

  public static function getProductsFile520Products(string $file_path): array
  {
    $input = file_get_contents($file_path);
    
    $data = json_decode($input);
    
    return collect($data->products)->pluck('url')->unique()->values()->toArray();
  }

  public static function getPriceHistoryExtractionFile520Products(string $file_path): array
  {
    $input = file_get_contents($file_path);
    
    $data = json_decode($input);
    
    $reference_date = new DateTime($data->reference_date);

    $priceHistoryExtractionData = collect($data->products)->map(function ($item) use ($reference_date) {
      return ['price' => $item->price, 'reference_date' => $reference_date];
    })->toArray();

    return $priceHistoryExtractionData;
  }

  public static function getProductsUpdateFile520Products(string $file_path): array
  {
    $input = file_get_contents($file_path);
    
    $data = json_decode($input);
    
    $reference_date = new DateTime($data->reference_date);

    $productsUpdateData = collect($data->products)->map(function ($item) use ($reference_date) {
      return ['last_price' => $item->price, 'last_date_price' => $reference_date, 'is_active' => true, 'available' => true];
    })->toArray();

    return $productsUpdateData;
  }


  

}

