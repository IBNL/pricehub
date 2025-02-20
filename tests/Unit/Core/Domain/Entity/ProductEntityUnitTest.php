<?php

namespace Tests\Unit\Core\Domain\Entity;

use Core\Domain\Entity\ProductEntity;
use Core\Domain\Notification\NotificationException;
use Core\Domain\ValueObject\ValueObjectUuid;
use DateTime;
#use PHPUnit\Framework\TestCase;
use Tests\TestCase; 
use PHPUnit\Framework\Attributes\DataProvider;


class ProductEntityUnitTest extends TestCase
{
  #[DataProvider('dataProviderAttributesCreate')]
  public function test_create(
    bool $is_active,
    string $url,
    string $name,
    string $slug,
    bool $available,
    ?ValueObjectUuid $ecommerce_id = null,
    ?ValueObjectUuid $brand_id = null,
    ?ValueObjectUuid $subcategory_id = null,
    ?DateTime $last_date_price = null,
    ?float $last_price = null,
    ?string $logo_from_ecommerce = null,
    ?string $logo = null,
  ): void {
    $productEntity = new ProductEntity(
      is_active: $is_active,
      url: $url,
      name: $name,
      slug: $slug,
      available: $available,
      ecommerce_id: $ecommerce_id,
      brand_id: $brand_id,
      subcategory_id: $subcategory_id,
      last_date_price: $last_date_price,
      last_price: $last_price,
      logo_from_ecommerce: $logo_from_ecommerce,
      logo: $logo,
    );

    $this->assertEquals($productEntity->is_active, $is_active);
    $this->assertEquals($productEntity->url, $url);
    $this->assertEquals($productEntity->name, $name);
    $this->assertEquals($productEntity->slug, $slug);
    $this->assertEquals($productEntity->available, $available);
    $this->assertEquals($productEntity->ecommerce_id, $ecommerce_id);
    $this->assertEquals($productEntity->brand_id, $brand_id);
    $this->assertEquals($productEntity->subcategory_id, $subcategory_id);
    $this->assertEquals($productEntity->last_date_price, $last_date_price);
    $this->assertEquals($productEntity->logo_from_ecommerce, $logo_from_ecommerce);
    $this->assertEquals($productEntity->logo, $logo);
  }

  public static function dataProviderAttributesCreate(): array
  {
    return [
      '0' => [
        'is_active' => false,
        'url' => 'https://www.ecommerce.com.br/produto/520990',
        'name' => 'memória ram redragon, 8gb, 3200mhz, ddr4, cl16, vermelho - gm-701',
        'slug' => 'memoria-ram-redragon-8gb-3200mhz-ddr4-cl16-vermelho-gm-701',
        'available' => false,
        'logo_from_ecommerce' => 'https://www.ecommerce.com.br/produtos/fotos/520990/memoria-redragon-8gb-3200mhz-ddr4-cl16-vermelho-gm-701_1708371448_original.jpg',
      ],
      '1' => [
        'is_active' => true,
        'url' => 'https://www.ecommerce.com.br/produto/520990',
        'name' => 'memória ram redragon, 8gb, 3200mhz, ddr4, cl16, vermelho - gm-701',
        'slug' => 'memoria-ram-redragon-8gb-3200mhz-ddr4-cl16-vermelho-gm-701',
        'available' => true,
        'logo_from_ecommerce' => 'https://www.ecommerce.com.br/produtos/fotos/520990/memoria-redragon-8gb-3200mhz-ddr4-cl16-vermelho-gm-701_1708371448_original.jpg',
      ],
      '2' => [
        'is_active' => true,
        'url' => 'https://www.ecommerce.com.br/produto/520990',
        'name' => 'memória ram redragon, 8gb, 3200mhz, ddr4, cl16, vermelho - gm-701',
        'slug' => 'memoria-ram-redragon-8gb-3200mhz-ddr4-cl16-vermelho-gm-701',
        'available' => true,
        'ecommerce_id' => new ValueObjectUuid('14f96b57-5517-11ef-a7cc-0242ac120002'),
        'brand_id' => new ValueObjectUuid('564dcda8-52a0-11ef-86b9-0242ac120003'),
        'subcategory_id' => new ValueObjectUuid('734c93d1-8b47-11ef-a076-0242ac120003'),
        'last_date_price' => new DateTime(),
        'last_price' => 10,
        'logo_from_ecommerce' => 'https://www.ecommerce.com.br/produtos/fotos/520990/memoria-redragon-8gb-3200mhz-ddr4-cl16-vermelho-gm-701_1708371448_original.jpg',
        'logo' => 'https://www.ecommerce.com.br/produtos/fotos/520990/memoria-redragon-8gb-3200mhz-ddr4-cl16-vermelho-gm-701_1708371448_original.jpg',
      ]
    ];
  }

  public function test_update(): void
  {
    $id = new ValueObjectUuid('00536f63-7418-442f-818e-3d34f877da0e');
    $productEntity = new ProductEntity(
      id: $id,
      is_active: false,
      url: 'https://www.ecommerce.com.br/produto/520990',
      name: 'memória ram redragon, 8gb, 3200mhz, ddr4, cl16, vermelho - gm-701',
      slug: 'memoria-ram-redragon-8gb-3200mhz-ddr4-cl16-vermelho-gm-701',
      available: false,
      ecommerce_id: new ValueObjectUuid('14f96b57-5517-11ef-a7cc-0242ac120002'),
      brand_id: new ValueObjectUuid('564dcda8-52a0-11ef-86b9-0242ac120003'),
      subcategory_id: new ValueObjectUuid('734c93d1-8b47-11ef-a076-0242ac120003'),
      last_date_price: null,
      last_price: null,
      logo_from_ecommerce: 'https://www.ecommerce.com.br/produtos/fotos/520990/memoria-redragon-8gb-3200mhz-ddr4-cl16-vermelho-gm-701_1708371448_original.jpg',
      logo: null,
    );
    $this->assertEquals($productEntity->id(), $id);
    $this->assertFalse(condition: $productEntity->available);
    $this->assertFalse(condition: $productEntity->is_active);
    $this->assertNull($productEntity->last_date_price);
    $this->assertNull($productEntity->last_price);
    $this->assertNotEmpty($productEntity->createdAt());


    $last_date_price = new DateTime();
    $last_price = 10.99;
    $productEntity->update(
      available: true,
      is_active: true,
      last_date_price: $last_date_price,
      last_price: $last_price
    );

    $this->assertEquals($productEntity->id(), $id);
    $this->assertTrue(condition: $productEntity->available);
    $this->assertTrue(condition: $productEntity->is_active);
    $this->assertEquals($productEntity->last_date_price, $last_date_price);
    $this->assertEquals($productEntity->last_price, $last_price);
    $this->assertNotEmpty($productEntity->createdAt());

  }

  //criar data provider para testar todas as regras
  public function test_exception():void
  {
    $this->expectException(NotificationException::class);
    new ProductEntity(
      is_active: false,
      url: 'a',
      name: 'memória ram redragon, 8gb, 3200mhz, ddr4, cl16, vermelho - gm-701',
      slug: 'memoria-ram-redragon-8gb-3200mhz-ddr4-cl16-vermelho-gm-701',
      available: false,
      ecommerce_id: new ValueObjectUuid('14f96b57-5517-11ef-a7cc-0242ac120002'),
      brand_id: new ValueObjectUuid('564dcda8-52a0-11ef-86b9-0242ac120003'),
      subcategory_id: new ValueObjectUuid('734c93d1-8b47-11ef-a076-0242ac120003'),
      last_date_price: null,
      last_price: null,
      logo_from_ecommerce: 'https://www.ecommerce.com.br/produtos/fotos/520990/memoria-redragon-8gb-3200mhz-ddr4-cl16-vermelho-gm-701_1708371448_original.jpg',
      logo: null,
    );
  }
}
