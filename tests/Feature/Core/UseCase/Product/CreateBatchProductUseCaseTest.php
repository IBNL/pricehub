<?php

namespace Tests\Feature\Core\UseCase\Product;

use App\Models\ProductModel;
use App\Repositories\Eloquent\ProductEloquentRepository;
use Core\Domain\ValueObject\ValueObjectUuid;
use Core\UseCase\Product\CreateBatchProductUseCase;
use Core\UseCase\Product\DTO\CreateBatchInputProductDTO;
use DateTime;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class CreateBatchProductUseCaseTest extends TestCase
{
  protected ProductEloquentRepository $productEloquentRepository;
  protected function setUp(): void
  {
    parent::setUp();

    $this->productEloquentRepository = new ProductEloquentRepository(new ProductModel());
  }
  public function test_create(): void
  {
    // arrange
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
    $createBatchBrandUseCase = new CreateBatchProductUseCase(
      productRepository: $this->productEloquentRepository
    );

    $productsInput = [
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

    //action
    $response = $createBatchBrandUseCase->execute(
      input: new CreateBatchInputProductDTO(
        data: $productsInput
      )
    );

    // assert
    $this->assertCount(3, $response->data);
    $this->assertDatabaseCount('products', 3);
  }
}
