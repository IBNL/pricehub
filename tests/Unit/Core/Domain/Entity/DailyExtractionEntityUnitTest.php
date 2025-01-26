<?php

namespace Tests\Unit\Core\Domain\Entity;

use Core\Domain\Entity\DailyExtractionEntity;
use Core\Domain\Notification\NotificationException;
use Core\Domain\ValueObject\ValueObjectUuid;
use DateTime;
use PHPUnit\Framework\Attributes\DataProvider;

use PHPUnit\Framework\TestCase;
use Illuminate\Support\Str;

class DailyExtractionEntityUnitTest extends TestCase
{

  #[DataProvider('dataProviderCreate')]
  public function test_create(
    ValueObjectUuid $extraction_id,
    DateTime $reference_date,
    array $extraction_success,
    array $send_to_process,
    array $input,
    array $output
  ): void {
    $dailyExtractionEntity = new DailyExtractionEntity(
      extraction_id: $extraction_id,
      reference_date: $reference_date,
      extraction_success: $extraction_success['value'],
      send_to_process: $send_to_process['value'],
      input: $input['value'],
      output: $output['value'],
    );

    $this->assertTrue(Str::isUuid($dailyExtractionEntity->id()));
    $this->assertEquals($dailyExtractionEntity->extraction_success, $extraction_success['expected']);
    $this->assertEquals($dailyExtractionEntity->send_to_process, $send_to_process['expected']);
    $this->assertEquals($dailyExtractionEntity->input, $input['expected']);
    $this->assertEquals($dailyExtractionEntity->output, $output['expected']);
    $this->assertNotEmpty($dailyExtractionEntity->createdAt());


  }

  public static function dataProviderCreate(): array
  {
    $input = '{
      "settings": "{\"url\": \"https://servicespub.prod.api.aws.grupokabum.com.br/catalog/v2/products-by-category/hardware/placa-de-video-vga?page_number={}&page_size=100&facet_filters=eyJrYWJ1bV9wcm9kdWN0IjpbInRydWUiXX0%3D&sort=price\", \"store\": {\"id\": \"564dcda8-52a0-11ef-86b9-0242ac120003\", \"name\": \"kabum\", \"filesystem\": {\"name\": \"fs\"}, \"browser_provider\": {\"name\": \"puppeteer\", \"options\": {\"headless\": false}}, \"html_parse_provider\": {\"name\": \"cheerio\"}}}",
      "file_path": "src/Storage/f0655dc2-537c-11ef-9847-0242ac120003/2024/8/14/",
      "ecommerce_id": "564dcda8-52a0-11ef-86b9-0242ac120003",
      "extraction_id": "f0655dc2-537c-11ef-9847-0242ac120003",
      "reference_date": "2024-08-14",
      "subcategory_id": "ff1c56e6-537b-11ef-9847-0242ac120003",
      "send_to_process": 1,
      "extraction_success": 0,
      "extraction_type_id": "29df6f23-52a0-11ef-86b9-0242ac120003",
      "extraction_type_name": "subcategoria"
  }';

    $output = '{
    "products": [
      {
        "url": "https://www.kabum.com.br/produto/506094",
        "name": "placa de vídeo quadro a800 pny nvidia, 40gb hbm2, 5120bits - vcna800-pb",
        "brand": "pny",
        "image": "https://images.kabum.com.br/produtos/fotos/506094/placa-de-video-a800-pny-nvidia-40gb-hbm2-5120bits-vcna800-pb_1718283743_original.jpg",
        "price": 92999.99,
        "availability": true
      }
    ],
    "ecommerce_id": "564dcda8-52a0-11ef-86b9-0242ac120003",
    "extraction_id": "f0655dc2-537c-11ef-9847-0242ac120003",
    "reference_date": "2024-08-14",
    "subcategory_id": "ff1c56e6-537b-11ef-9847-0242ac120003"
  }';
    return [
      'item before sending queue' => [
        'extraction_id' => ValueObjectUuid::create(),
        'reference_date' => new DateTime(),
        'input' => ['value' => $input, 'expected' => $input],
        'output' => ['value' => '', 'expected' => ''],
        'extraction_success' => ['value' => false, 'expected' => false],
        'send_to_process' => ['value' => true, 'expected' => true]
      ],
      'item after processed queue success' => [
        'extraction_id' => ValueObjectUuid::create(),
        'reference_date' => new DateTime(),
        'input' => ['value' => $input, 'expected' => $input],
        'output' => ['value' => $output, 'expected' => $output],
        'extraction_success' => ['value' => true, 'expected' => true],
        'send_to_process' => ['value' => true, 'expected' => true]
      ],
      'item after processed queue failed' => [
        'extraction_id' => ValueObjectUuid::create(),
        'reference_date' => new DateTime(),
        'input' => ['value' => $input, 'expected' => $input],
        'output' => ['value' => '', 'expected' => ''],
        'extraction_success' => ['value' => false, 'expected' => false],
        'send_to_process' => ['value' => true, 'expected' => true]
      ],
    ];
  }

  /*#[DataProvider('dataProviderValidator')]
  public function testValidator(
    ValueObjectUuid $extraction_id,
    DateTime $reference_date,
  ): void {
    $this->expectException(NotificationException::class);

    $dailyExtractionEntity = new DailyExtractionEntity(
      extraction_id: $extraction_id,
      reference_date: $reference_date,
    );

  }

  public static function dataProviderValidator(): array
  {
    return [
      'empty extraction_id and empty reference_date' => [
        'extraction_id' => ValueObjectUuid::create(),
        'reference_date' => new DateTime(),
      ],
    ];
  }*/
}

