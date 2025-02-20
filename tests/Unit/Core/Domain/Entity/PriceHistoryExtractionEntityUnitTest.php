<?php

namespace Tests\Unit\Core\Domain\Entity;

use Core\Domain\Entity\PriceHistoryExtractionEntity;
use Core\Domain\Notification\NotificationException;
use Core\Domain\ValueObject\ValueObjectUuid;
use DateTime;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class PriceHistoryExtractionEntityUnitTest extends TestCase
{
  #[DataProvider('dataProviderAttributesCreate')]
  public function test_create(
    ValueObjectUuid $extraction_id,
    ValueObjectUuid $product_id,
    DateTime $reference_date,
    float $price
  ): void {
    $priceHistoryExtractionEntity = new PriceHistoryExtractionEntity(
      extraction_id: $extraction_id,
      product_id: $product_id,
      reference_date: $reference_date,
      price: $price
    );

    $this->assertEquals($priceHistoryExtractionEntity->extraction_id, $extraction_id);
    $this->assertEquals($priceHistoryExtractionEntity->product_id, $product_id);
    $this->assertEquals($priceHistoryExtractionEntity->reference_date, $reference_date);
    $this->assertEquals($priceHistoryExtractionEntity->price, $price);
  }

  public static function dataProviderAttributesCreate(): array
  {
    return [
      '0' => [
        'extraction_id' => new ValueObjectUuid('5bef40b1-5cf9-474b-bbc0-b28f3555d6c8'),
        'product_id' => new ValueObjectUuid('79b91ef9-97f4-455b-85ed-7a4cff3ac460'),
        'reference_date' => new DateTime(),
        'price' => 10.80
      ],
      '1' => [
        'extraction_id' => new ValueObjectUuid('0ae722b8-acd8-4f8c-95c5-8a73f77ee0b7'),
        'product_id' => new ValueObjectUuid('652cb5a7-28ad-4280-b6e6-31e1076de470'),
        'reference_date' => new DateTime(),
        'price' => 54
      ]
    ];
  }

  #[DataProvider('dataProviderAttributesException')]
  public function test_validation(
    ValueObjectUuid $extraction_id,
    ValueObjectUuid $product_id,
    DateTime $reference_date,
    float $price
  ): void {
    $this->expectException(NotificationException::class);
    new PriceHistoryExtractionEntity(
      extraction_id: $extraction_id,
      product_id: $product_id,
      reference_date: $reference_date,
      price: $price
    );
  }

  public static function dataProviderAttributesException(): array
  {
    return [
      '0' => [
        'extraction_id' => new ValueObjectUuid('5bef40b1-5cf9-474b-bbc0-b28f3555d6c8'),
        'product_id' => new ValueObjectUuid('79b91ef9-97f4-455b-85ed-7a4cff3ac460'),
        'reference_date' => new DateTime(),
        'price' => 0
      ],
      '1' => [
        'extraction_id' => new ValueObjectUuid('5bef40b1-5cf9-474b-bbc0-b28f3555d6c8'),
        'product_id' => new ValueObjectUuid('79b91ef9-97f4-455b-85ed-7a4cff3ac460'),
        'reference_date' => new DateTime(),
        'price' => -10
      ],
    ];
  }
}
