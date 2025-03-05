<?php

namespace Tests\Unit\Core\UseCase\Product;

use Core\Domain\Entity\ProductEntity;
use Core\Domain\Repository\ProductRepositoryInterface;
use Core\Domain\ValueObject\ValueObjectUuid;
use Core\UseCase\Product\CreateBatchProductUseCase;

use Core\UseCase\Product\DTO\CreateBatchInputProductDTO;
use Core\UseCase\Product\DTO\CreateBatchOutputProductDTO;
use DateTime;
use Mockery;
#use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use stdClass;

class CreateBatchProductUseCaseUnitTest extends TestCase
{

  public function test_create(): void
  {
    // arrange
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
        'brand_id' => new ValueObjectUuid('564dcda8-52a0-11ef-86b9-0242ac120003'),
        'subcategory_id' => new ValueObjectUuid('734c93d1-8b47-11ef-a076-0242ac120003'),
        'last_date_price' => new DateTime(),
        'last_price' => 10,
        'logo_from_ecommerce' => 'https://www.ecommerce.com.br/produtos/fotos/520992/memoria-redragon-8gb-3200mhz-ddr4-cl16-vermelho-gm-701_1708371448_original.jpg',
        'logo' => 'https://www.ecommerce.com.br/produtos/fotos/520990/memoria-redragon-8gb-3200mhz-ddr4-cl16-vermelho-gm-701_1708371448_original.jpg',
      ]
    ];

    $mockEntities = array_map(function ($product) {
      return Mockery::mock(ProductEntity::class, [
        'is_active' => $product['is_active'],
        'url' => $product['url'],
        'name' => $product['name'],
        'slug' => $product['slug'],
        'available' => $product['available'],
        'ecommerce_id' => $product['ecommerce_id'] ?? null,
        'brand_id' => $product['brand_id'] ?? null,
        'subcategory_id' => $product['subcategory_id'] ?? null,
        'last_date_price' => $product['last_date_price'] ?? null,
        'last_price' => $product['last_price'] ?? null,
        'logo_from_ecommerce' => $product['logo_from_ecommerce'] ?? null,
        'logo' => $product['logo'] ?? null,
      ]);
    }, $productsInput);

    $mockProductRepository = Mockery::mock(stdClass::class, ProductRepositoryInterface::class);
    $mockProductRepository->shouldReceive('insertBatch')
      ->once()
      ->andReturn($mockEntities);

    $useCase = new CreateBatchProductUseCase(
      productRepository: $mockProductRepository
    );

    $mockCreateInputProductDTO = Mockery::mock(CreateBatchInputProductDTO::class, [$productsInput]);

    //action
    $response = $useCase->execute(input: $mockCreateInputProductDTO);
  
    //assert
    $this->assertInstanceOf(CreateBatchOutputProductDTO::class, $response);
    $this->assertCount(3, $response->data);
  
    Mockery::close();
  }
}
