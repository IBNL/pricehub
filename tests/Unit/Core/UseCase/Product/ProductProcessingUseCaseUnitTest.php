<?php

namespace Tests\Unit\Core\UseCase\Product;

use Core\Domain\Entity\BrandEntity;
use Core\Domain\Repository\BrandRepositoryInterface;
use Core\Domain\Repository\DailyExtractionRepositoryInterface;
use Core\Domain\Repository\ProductRepositoryInterface;
use Core\UseCase\Product\DTO\ProductProcessingInputDto;
use Core\UseCase\Product\ProductProcessingUseCase;
use Mockery;
#use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use stdClass;


class ProductProcessingUseCaseUnitTest extends TestCase
{
  #public function test_create(): void
  public function create(): void
  {
    $brandsNeedCreate = [
      ['name' => 'rise mode'],
      ['name' => 'brazil pc']
    ];

    $mockBrandRepository = Mockery::mock(stdClass::class, BrandRepositoryInterface::class);
    $mockBrandRepository->shouldReceive('getBrandNeedCreate')
      ->once()
      ->andReturn($brandsNeedCreate);

    $mockBrandRepository->shouldReceive('insertBatch')
      ->once();

    $brandsInDatabase = [
      ['id' => '272dd7e1-0328-4fcc-a058-69fec2a18734', 'name' => 'rise mode'],
      ['id' => '353a70f0-7bb0-4e7d-acac-80ffbbff4a4c', 'name' => 'brazil pc']
    ];
    $mockBrandRepository->shouldReceive('index')
      ->once()
      ->andReturn($brandsInDatabase);

    $mockProductRepository = Mockery::mock(stdClass::class, ProductRepositoryInterface::class);
    $productsInDatabaseBeforeCreate = [
      [
        "id" => "564dcda8-52a0-11ef-86b9-0242ac120003",
        "url" => "https://www.kabum.com.br/produto/102447",
        "name" => "memória ram para notebook rise mode, 4gb, 1600mhz, ddr3, cl11 - rm-d3-4g1600n",
        "brand" => "rise mode",
        "image" => "https://images.kabum.com.br/produtos/fotos/102447/memoria-rise-mode-4gb-1600mhz-ddr3-notebook-rm-d3-4g1600n_memoria-rise-mode-4gb-1600mhz-ddr3-notebook-rm-d3-4g1600n_1563971973_original.jpg",
        "price" => 49.99,
        "availability" => true,
        "brand_id" => "272dd7e1-0328-4fcc-a058-69fec2a18734",
        "extraction_id" => "99803007-52a0-11ef-86b9-0242ac120003",
        "ecommerce_id" => "564dcda8-52a0-11ef-86b9-0242ac120003",
        "subcategory_id" => "0f1ff8d3-52a0-11ef-86b9-0242ac120003",
        "is_active" => true,
        "available" => true,
        "logo_from_ecommerce" => "https://images.kabum.com.br/produtos/fotos/102447/memoria-rise-mode-4gb-1600mhz-ddr3-notebook-rm-d3-4g1600n_memoria-rise-mode-4gb-1600mhz-ddr3-notebook-rm-d3-4g1600n_1563971973_original.jpg",
      ]
    ];//atualizar de acordo com o index

    $productsInDatabaseAfterCreate = [
      [
        "id" => "564dcda8-52a0-11ef-86b9-0242ac120003",
        "is_active" => true,
        "url" => "https://www.kabum.com.br/produto/102447",
        "name" => "memória ram para notebook rise mode, 4gb, 1600mhz, ddr3, cl11 - rm-d3-4g1600n",
        "slug" => "memoria-ram-para-notebook-rise-mode-4gb-1600mhz-ddr3-cl11-rm-d3-4g1600n",
        "available" => true,
        "ecommerce_id" => "564dcda8-52a0-11ef-86b9-0242ac120003",
        "brand_id" => "272dd7e1-0328-4fcc-a058-69fec2a18734",
        "subcategory_id" => "0f1ff8d3-52a0-11ef-86b9-0242ac120003",
        "last_date_price" => "2024-08-19",
        "last_price" => 49.99,
        "logo_from_ecommerce" => "https://images.kabum.com.br/produtos/fotos/102447/memoria-rise-mode-4gb-1600mhz-ddr3-notebook-rm-d3-4g1600n_memoria-rise-mode-4gb-1600mhz-ddr3-notebook-rm-d3-4g1600n_1563971973_original.jpg",
        "logo" => null,
      ],
      [
        "id" => "d181a992-1d34-4d4f-bc8c-4170e9ff5ccb",
        "is_active" => true,
        "url" => "https://www.kabum.com.br/produto/99428",
        "name" => "memória ram rise mode, 4gb, 1600mhz, ddr3, cl11 - rm-d3-4g1600v",
        "slug" => "memoria-ram-rise-mode-4gb-1600mhz-ddr3-cl11-rm-d3-4g1600v",
        "available" => true,
        "ecommerce_id" => "564dcda8-52a0-11ef-86b9-0242ac120003",
        "brand_id" => "272dd7e1-0328-4fcc-a058-69fec2a18734",
        "subcategory_id" => "0f1ff8d3-52a0-11ef-86b9-0242ac120003",
        "last_date_price" => "2024-08-19",
        "last_price" => 69.99,
        "logo_from_ecommerce" => "https://images.kabum.com.br/produtos/fotos/99428/memoria-rise-mode-4gb-1600mhz-ddr3-rm-d3-4g1600v_1563977068_original.jpg",
        "logo" => null,
      ],
      [
        "id" => "e3d2a101-0d7d-45ef-86d4-9752b417f3f6",
        "is_active" => true,
        "url" => "https://www.kabum.com.br/produto/551170",
        "name" => "memória para notebook brazilpc, 4gb, 1333mhz, ddr3l, cl09, 1.35v - bpc1333d3lcl9s/4g",
        "slug" => "memoria-para-notebook-brazilpc-4gb-1333mhz-ddr3l-cl09-1-35v-bpc1333d3lcl9s-4g",
        "available" => true,
        "ecommerce_id" => "564dcda8-52a0-11ef-86b9-0242ac120003",
        "brand_id" => "353a70f0-7bb0-4e7d-acac-80ffbbff4a4c",
        "subcategory_id" => "0f1ff8d3-52a0-11ef-86b9-0242ac120003",
        "last_date_price" => "2024-08-19",
        "last_price" => 40,
        "logo_from_ecommerce" => "https://images.kabum.com.br/produtos/fotos/551170/memoria-para-notebook-brazilpc-4gb-1333mhz-ddr3l-cl09-1-35v-bpc1333d3lcl9s-4g_1719518450_original.jpg",
        "logo" => null,
      ]
    ];

    $mockProductRepository->shouldReceive('index')
      ->once()
      ->andReturn($productsInDatabaseBeforeCreate, $productsInDatabaseAfterCreate);


    $mockProductRepository->shouldReceive('insertBatch')
      ->once();


    $mockDailyExtractionRepository = Mockery::mock(stdClass::class, DailyExtractionRepositoryInterface::class);

    $useCase = new ProductProcessingUseCase(
      brandRepository: $mockBrandRepository,
      productRepository: $mockProductRepository,
      dailyExtractionRepository: $mockDailyExtractionRepository
    );

    /*$mockInputDto = Mockery::mock(ProductProcessingInputDto::class, [
      file_get_contents(base_path('tests/files/outputProductProcessing.json'))
    ]);*/
    $mockInputDto = Mockery::mock(ProductProcessingInputDto::class, [
      '{
        "products": [
          {
            "url": "https://www.kabum.com.br/produto/102447",
            "name": "memória ram para notebook rise mode, 4gb, 1600mhz, ddr3, cl11 - rm-d3-4g1600n",
            "brand": "rise mode",
            "image": "https://images.kabum.com.br/produtos/fotos/102447/memoria-rise-mode-4gb-1600mhz-ddr3-notebook-rm-d3-4g1600n_memoria-rise-mode-4gb-1600mhz-ddr3-notebook-rm-d3-4g1600n_1563971973_original.jpg",
            "price": 49.99,
            "availability": true
          },
          {
            "url": "https://www.kabum.com.br/produto/99428",
            "name": "memória ram rise mode, 4gb, 1600mhz, ddr3, cl11 - rm-d3-4g1600v",
            "brand": "rise mode",
            "image": "https://images.kabum.com.br/produtos/fotos/99428/memoria-rise-mode-4gb-1600mhz-ddr3-rm-d3-4g1600v_1563977068_original.jpg",
            "price": 69.99,
            "availability": true
          },
          {
            "url": "https://www.kabum.com.br/produto/551170",
            "name": "memória para notebook brazilpc, 4gb, 1333mhz, ddr3l, cl09, 1.35v - bpc1333d3lcl9s/4g",
            "brand": "brazil pc",
            "image": "https://images.kabum.com.br/produtos/fotos/551170/memoria-para-notebook-brazilpc-4gb-1333mhz-ddr3l-cl09-1-35v-bpc1333d3lcl9s-4g_1719518450_original.jpg",
            "price": 40,
            "availability": true
          }
        ],
        "ecommerce_id": "564dcda8-52a0-11ef-86b9-0242ac120003",
        "extraction_id": "99803007-52a0-11ef-86b9-0242ac120003",
        "reference_date": "2024-08-20",
        "subcategory_id": "0f1ff8d3-52a0-11ef-86b9-0242ac120003"
      }'
    ]);

    $useCase->execute(input: $mockInputDto);
  }
}
