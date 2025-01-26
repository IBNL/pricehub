<?php

namespace Tests\Unit\Core\UseCase\DailyExtraction;

use Core\Domain\Repository\DailyExtractionInterface;
use Core\Domain\Repository\ExtractionRepositoryInterface;
use Core\UseCase\DailyExtraction\CreateDailyExtractionUseCase;
use Core\UseCase\DailyExtraction\DTO\CreateBatchOutputDailyExtractionDTO;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

class CreateDailyExtractionUseCaseUnitTest extends TestCase
{

  public function test_create_empty_daily_extraction(): void
  {
    // arrange
    $mockExtractionRepository = Mockery::mock(stdClass::class, ExtractionRepositoryInterface::class);
    $mockExtractionRepository->shouldReceive('createDailyExtraction')
      ->once()
      ->andReturn([]);


    $mockDailyExtractionRepository = Mockery::mock(stdClass::class, DailyExtractionInterface::class);

    $useCase = new CreateDailyExtractionUseCase(
      extractionRepository: $mockExtractionRepository,
      dailyExtractionRepository: $mockDailyExtractionRepository
    );

    // action
    $response = $useCase->execute();

    // assert
    $this->assertInstanceOf(CreateBatchOutputDailyExtractionDTO::class, $response);
    $this->assertCount(0, $response->data);
    Mockery::close();
  }

  public function test_create(): void
  {
    // arrange
    $mockExtractionRepository = Mockery::mock(stdClass::class, ExtractionRepositoryInterface::class);
    $mockExtractionRepository->shouldReceive('createDailyExtraction')
      ->once()
      ->andReturn($this->getDailyExtractionData());


    $mockDailyExtractionRepository = Mockery::mock(stdClass::class, DailyExtractionInterface::class);
    $mockDailyExtractionRepository->shouldReceive('insertBatch')
      ->once()
      ->andReturn($this->getDailyExtractionData());

    $useCase = new CreateDailyExtractionUseCase(
      extractionRepository: $mockExtractionRepository,
      dailyExtractionRepository: $mockDailyExtractionRepository
    );

    // action
    $response = $useCase->execute();

    // assert
    $this->assertInstanceOf(CreateBatchOutputDailyExtractionDTO::class, $response);
    $this->assertCount(count($this->getDailyExtractionData()), $response->data);
    Mockery::close();

  }

  private function getDailyExtractionData(): array
  {
    $data = [
      [
        "ecommerce_id" => "734c93d1-8b47-11ef-a076-0242ac120003",
        "extraction_id" => "0ae34de8-8c0e-11ef-b927-0242ac120002",
        "subcategory_id" => "4eec0504-868e-11ef-830e-0242ac120002",
        "extraction_type_id" => "29df6f23-52a0-11ef-86b9-0242ac120003",
        "extraction_type_name" => "subcategoria",
        "reference_date" => "2025-01-21",
        "send_to_process" => 1,
        "extraction_success" => 0,
        "file_path" => "src/Storage/0ae34de8-8c0e-11ef-b927-0242ac120002/2025/1/21/",
        "settings" => [
          "url" => "https://www.terabyteshop.com.br/hardware/fontes",
          "store" => [
            "id" => "734c93d1-8b47-11ef-a076-0242ac120003",
            "name" => "terabyteshop",
            "filesystem" => [
              "name" => "fs"
            ],
            "browser_provider" => [
              "name" => "puppeteer",
              "options" => [
                "headless" => false
              ]
            ],
            "html_parse_provider" => [
              "name" => "cheerio"
            ]
          ]
        ]
      ],
      [
        "ecommerce_id" => "14f96b57-5517-11ef-a7cc-0242ac120002",
        "extraction_id" => "0ccaf743-872a-11ef-ab2a-0242ac120003",
        "subcategory_id" => "4eec0504-868e-11ef-830e-0242ac120002",
        "extraction_type_id" => "29df6f23-52a0-11ef-86b9-0242ac120003",
        "extraction_type_name" => "subcategoria",
        "reference_date" => "2025-01-21",
        "send_to_process" => 1,
        "extraction_success" => 0,
        "file_path" => "src/Storage/0ccaf743-872a-11ef-ab2a-0242ac120003/2025/1/21/",
        "settings" => [
          "url" => "https://www.pichau.com.br/hardware/fonte?sort=price-asc&page={}",
          "store" => [
            "id" => "14f96b57-5517-11ef-a7cc-0242ac120002",
            "name" => "pichau",
            "filesystem" => [
              "name" => "fs"
            ],
            "browser_provider" => [
              "name" => "puppeteer",
              "options" => [
                "headless" => false
              ]
            ],
            "html_parse_provider" => [
              "name" => "cheerio"
            ]
          ]
        ]
      ]
    ];
    return $data;
  }
}
