<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\ProductModel;
use App\Repositories\Eloquent\ProductEloquentRepository;
use Core\Domain\Entity\ProductEntity;
use Core\Domain\Repository\ProductRepositoryInterface;
use Core\Domain\ValueObject\ValueObjectUuid;
use DateTime;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;


class ProductEloquentRepositoryTest extends TestCase
{
  protected $repository;

  protected function setUp(): void
  {
    parent::setUp();

    $this->repository = new ProductEloquentRepository(new ProductModel());
  }

  public function testCheckImplementsInterfaceRepository()
  {
    $this->assertInstanceOf(ProductRepositoryInterface::class, $this->repository);
  }

  public function testInsertBatch()
  {
    //arrange
    $seeders = [
      'EcommerceSeeder',
      'BrandSeeder',
      'ExtractionTypesSeeder',
      'CategoriesSeeder',
      'SubcategoriesSeeder',
      'ExtractionSeeder',
    ];

    foreach ($seeders as $seeder) {
      Artisan::call('db:seed', ['--class' => $seeder]);
    }

    $products = [
      [
        'is_active' => true,
        'url' => 'https://www.ecommerce.com.br/produto/520990',
        'name' => 'memória ram redragon, 8gb, 3200mhz, ddr4, cl16, vermelho - gm-701',
        'slug' => 'memoria-ram-redragon-8gb-3200mhz-ddr4-cl16-vermelho-gm-701',
        'available' => true,
        'logo_from_ecommerce' => 'https://www.ecommerce.com.br/produtos/fotos/520990/memoria-redragon-8gb-3200mhz-ddr4-cl16-vermelho-gm-701_1708371448_original.jpg',
      ],
      [
        'is_active' => true,
        'url' => 'https://www.ecommerce.com.br/produto/520991',
        'name' => 'memória ram redragon, 8gb, 3200mhz, ddr4, cl16, vermelho - gm-701',
        'slug' => 'memoria-ram-redragon-8gb-3200mhz-ddr4-cl16-vermelho-gm-701',
        'available' => true,
        'logo_from_ecommerce' => 'https://www.ecommerce.com.br/produtos/fotos/520990/memoria-redragon-8gb-3200mhz-ddr4-cl16-vermelho-gm-701_1708371448_original.jpg',
      ],
      [
        'is_active' => true,
        'url' => 'https://www.ecommerce.com.br/produto/520992',
        'name' => 'memória ram redragon, 8gb, 3200mhz, ddr4, cl16, vermelho - gm-701',
        'slug' => 'memoria-ram-redragon-8gb-3200mhz-ddr4-cl16-vermelho-gm-701',
        'available' => true,
        'ecommerce_id' => new ValueObjectUuid('14f96b57-5517-11ef-a7cc-0242ac120002'),
        'brand_id' => new ValueObjectUuid('272dd7e1-0328-4fcc-a058-69fec2a18734'),
        'subcategory_id' => new ValueObjectUuid('0f1ff8d3-52a0-11ef-86b9-0242ac120003'),
        'last_date_price' => new DateTime(),
        'last_price' => 10,
        'logo_from_ecommerce' => 'https://www.ecommerce.com.br/produtos/fotos/520992/memoria-redragon-8gb-3200mhz-ddr4-cl16-vermelho-gm-701_1708371448_original.jpg',
        'logo' => 'https://www.ecommerce.com.br/produtos/fotos/520990/memoria-redragon-8gb-3200mhz-ddr4-cl16-vermelho-gm-701_1708371448_original.jpg',
      ]
    ];

    $productEntities = [];

    foreach ($products as $product) {
      $productEntity = new ProductEntity(
        is_active: $product['is_active'],
        url: $product['url'],
        name: $product['name'],
        slug: $product['slug'],
        available: $product['available'],
        ecommerce_id: $product['ecommerce_id'] ?? null,
        brand_id: $product['brand_id'] ?? null,
        subcategory_id: $product['subcategory_id'] ?? null,
        last_date_price: $product['last_date_price'] ?? null,
        last_price: $product['last_price'] ?? null,
        logo_from_ecommerce: $product['logo_from_ecommerce'] ?? null,
        logo: $product['logo'] ?? null,
      );
      array_push($productEntities, $productEntity);
    }

    //action
    $response = $this->repository->insertBatch($productEntities);

    //assert
    $this->assertDatabaseCount('products', 3);

    foreach ($response as $key => $product) {
      $this->assertEquals($productEntities[$key]->id, $product['id']);
      $this->assertEquals($productEntities[$key]->is_active, $product['is_active']);
      $this->assertEquals($productEntities[$key]->url, $product['url']);
      $this->assertEquals($productEntities[$key]->name, $product['name']);
      $this->assertEquals($productEntities[$key]->slug, $product['slug']);
      $this->assertEquals($productEntities[$key]->available, $product['available']);
      $this->assertEquals($productEntities[$key]->ecommerce_id, $product['ecommerce_id']);
      $this->assertEquals($productEntities[$key]->brand_id, $product['brand_id']);
      $this->assertEquals($productEntities[$key]->subcategory_id, $product['subcategory_id']);
      $this->assertEquals($productEntities[$key]->last_date_price, $product['last_date_price']);
      $this->assertEquals($productEntities[$key]->last_price, $product['last_price']);
      $this->assertEquals($productEntities[$key]->logo_from_ecommerce, $product['logo_from_ecommerce']);
      $this->assertEquals($productEntities[$key]->logo, $product['logo']);
      $this->assertNotEmpty($productEntities[$key]->created_at);
    }
  }

  #[DataProvider('dataProviderIndex')]
  public function testIndex(
    array $filterData
  ): void {
    // arrange
    $seeders = [
      'EcommerceSeeder',
      'BrandSeeder',
      'ExtractionTypesSeeder',
      'CategoriesSeeder',
      'SubcategoriesSeeder',
      'ExtractionSeeder',
      'ProductSeeder'
    ];

    foreach ($seeders as $seeder) {
      Artisan::call('db:seed', ['--class' => $seeder]);
    }

    // action
    $response = $this->repository->index(filterData: $filterData['value']);

    // assert
    $this->assertCount($filterData['expectedCount'], $response);
  }

  public static function dataProviderIndex(): array
  {
    return [
      'empty filter' => [
        'filterData' => [
          'value' => [],
          'expectedCount' => 3,
        ],
      ],
      'filter valid subcategory_id' => [
        'filterData' => [
          'value' => [
            'subcategory_id' => '0f1ff8d3-52a0-11ef-86b9-0242ac120003'
          ],
          'expectedCount' => 2,
        ],
      ],
      'filter invalid subcategory_id' => [
        'filterData' => [
          'value' => [
            'subcategory_id' => '0f1ff8d3-52a0-11ef-86b9-10'
          ],
          'expectedCount' => 0,
        ],
      ],
      'filter available true' => [
        'filterData' => [
          'value' => [
            'available' => true
          ],
          'expectedCount' => 2,
        ],
      ],
      'filter available 1' => [
        'filterData' => [
          'value' => [
            'available' => 1
          ],
          'expectedCount' => 2,
        ],
      ],
      'filter available false' => [
        'filterData' => [
          'value' => [
            'available' => false
          ],
          'expectedCount' => 1,
        ],
      ],
      'filter available 0' => [
        'filterData' => [
          'value' => [
            'available' => 0
          ],
          'expectedCount' => 1,
        ],
      ],
    ];
  }

  public function test_update_batch(): void
  {
    // arrange
    $seeders = [
      'EcommerceSeeder',
      'BrandSeeder',
      'ExtractionTypesSeeder',
      'CategoriesSeeder',
      'SubcategoriesSeeder',
      'ExtractionSeeder',
      'ProductSeeder'
    ];

    foreach ($seeders as $seeder) {
      Artisan::call('db:seed', ['--class' => $seeder]);
    }
    $productsInDatabase = ProductModel::all();

    $productsEntitiesToUpdate = [];
    $last_date_price = new DateTime();
    $last_price = 999;

    foreach ($productsInDatabase as $product) {
      $productEntity = new ProductEntity(
        id: new ValueObjectUuid($product['id']),
        is_active: $product['is_active'],
        url: $product['url'],
        name: $product['name'],
        slug: $product['slug'],
        available: $product['available'],
        ecommerce_id: new ValueObjectUuid($product['ecommerce_id']),
        brand_id: new ValueObjectUuid($product['brand_id']),
        subcategory_id: new ValueObjectUuid($product['subcategory_id']),
        last_date_price: $product['last_date_price'],
        last_price: $product['last_price'],
        logo_from_ecommerce: $product['logo_from_ecommerce'],
        logo: $product['logo'],
      );

      $productEntity->update(
        available: true,
        is_active: true,
        last_date_price: $last_date_price,
        last_price: $last_price
      );
      array_push($productsEntitiesToUpdate, $productEntity);
    }

    // action
    $this->repository->updateBatch(data: $productsEntitiesToUpdate);

    // assert
    $this->assertDatabaseCount('products', 3);
    $this->assertDatabaseHas('products', [
      'last_price' => $last_price,
      'last_date_price' => $last_date_price
    ]);

  }

}
