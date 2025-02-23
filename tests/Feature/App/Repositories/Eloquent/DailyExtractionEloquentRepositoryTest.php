<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\DailyExtractionModel;
use App\Models\ExtractionModel;
use App\Repositories\Eloquent\DailyExtractionEloquentRepository;
use App\Repositories\Eloquent\ExtractionEloquentRepository;
use Core\Domain\Entity\DailyExtractionEntity;
use Core\Domain\Repository\DailyExtractionRepositoryInterface;
use Core\Domain\ValueObject\ValueObjectUuid;
use DateTime;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;


class DailyExtractionEloquentRepositoryTest extends TestCase
{
  protected DailyExtractionEloquentRepository $dailyExtractionRepository;
  protected ExtractionEloquentRepository $extractionRepository;

  protected function setUp(): void
  {
    parent::setUp();
    Artisan::call('db:seed');
    $this->dailyExtractionRepository = new DailyExtractionEloquentRepository(new DailyExtractionModel());
    $this->extractionRepository = new ExtractionEloquentRepository(new ExtractionModel());

  }

  public function testCheckImplementsInterfaceRepository()
  {
    $this->assertInstanceOf(DailyExtractionRepositoryInterface::class, $this->dailyExtractionRepository);
  }

  public function testInsertBatch()
  {
    //arrange
    $dailyExtractions = $this->extractionRepository->createDailyExtraction();

    $dailyExtractionEntities = [];
    foreach ($dailyExtractions as $dailyExtraction) {
      $dailyExtractionEntity = new DailyExtractionEntity(
        extraction_id: new ValueObjectUuid($dailyExtraction['extraction_id']),
        reference_date: new DateTime($dailyExtraction['reference_date']),
        send_to_process: $dailyExtraction['send_to_process'],
        extraction_success: $dailyExtraction['extraction_success'],
        input: json_encode($dailyExtraction),
      );
      array_push($dailyExtractionEntities, $dailyExtractionEntity);
    }

    //action
    $response = $this->dailyExtractionRepository->insertBatch($dailyExtractionEntities);

    //assert
    $this->assertDatabaseCount('daily_extractions', count($dailyExtractions));

    foreach ($response as $key => $dailyExtraction) {
      $this->assertEquals($dailyExtractionEntities[$key]->id, $dailyExtraction['id']);
      $this->assertEquals($dailyExtractionEntities[$key]->extraction_id, $dailyExtraction['extraction_id']);
      $this->assertEquals($dailyExtractionEntities[$key]->input, $dailyExtraction['input']);
      $this->assertEquals($dailyExtractionEntities[$key]->output, $dailyExtraction['output']);
      $this->assertEquals($dailyExtractionEntities[$key]->extraction_success, $dailyExtraction['extraction_success']);
      $this->assertEquals($dailyExtractionEntities[$key]->reference_date, $dailyExtraction['reference_date']);
      $this->assertEquals($dailyExtractionEntities[$key]->send_to_process, $dailyExtraction['send_to_process']);

    }

  }

  #[DataProvider('dataProviderFindByColumns')]
  public function test_find_by_columns(
    array $columns
  ): void {
    // arrange
    DailyExtractionModel::insert([
      [
        'id' => 'e27f1d4c-cf62-4b8e-b9c2-688f3f49314f',
        'extraction_id' => '0ae34de8-8c0e-11ef-b927-0242ac120002',
        'reference_date' => '2025-02-09',
        'send_to_process' => 1,
        'extraction_success' => 0,
        'input' => null,
        'output' => null
      ],
      [
        'id' => '31f41cf4-e3c9-4ed9-b0c3-d7aafba76ff4',
        'extraction_id' => '0ae34de8-8c0e-11ef-b927-0242ac120002',
        'reference_date' => '2025-02-10',
        'send_to_process' => 1,
        'extraction_success' => 0,
        'input' => null,
        'output' => null
      ],
      [
        'id' => '0546cf92-4f3a-44a5-9c6e-351e8a6df4a3',
        'extraction_id' => '199eddc9-868f-11ef-830e-0242ac120002',
        'reference_date' => '2025-02-10',
        'send_to_process' => 1,
        'extraction_success' => 0,
        'input' => null,
        'output' => null
      ]
    ]);

    // action
    $response = $this->dailyExtractionRepository->findByColumns(columns: $columns['value']);

    // assert
    if (!$columns['expectedData']) {
      $this->assertNull($response);
    }

    if ($columns['expectedData'] == 'entity') {
      $this->assertInstanceOf(DailyExtractionEntity::class, $response);
    }


  }

  public static function dataProviderFindByColumns(): array
  {
    return [
      'one extraction_id and one reference date return entity' => [
        'columns' => [
          'value' => [
            'extraction_id' => '0ae34de8-8c0e-11ef-b927-0242ac120002',
            'reference_date' => '2025-02-10'
          ],
          'expectedData' => 'entity',
        ],
      ],
      'invalid extraction_id and valid reference date return null ' => [
        'columns' => [
          'value' => [
            'extraction_id' => '0ae34de8-8c0e-11ef-b927-0242ac120003',
            'reference_date' => '2025-02-10'
          ],
          'expectedData' => null,
        ],
      ],
    ];
  }

  public function test_update()
  {
    DailyExtractionModel::insert([
      [
        'id' => 'e27f1d4c-cf62-4b8e-b9c2-688f3f49314f',
        'extraction_id' => '99803007-52a0-11ef-86b9-0242ac120003',
        'reference_date' => '2024-08-20',
        'send_to_process' => 1,
        'extraction_success' => 0,
        'input' => '{"settings": "{"url": "https://servicespub.prod.api.aws.grupokabum.com.br/catalog/v2/products-by-category/hardware/memoria-ram?page_number={}&page_size=100&facet_filters=eyJrYWJ1bV9wcm9kdWN0IjpbInRydWUiXX0%3D&sort=price", "store": {"id": "564dcda8-52a0-11ef-86b9-0242ac120003", "name": "kabum", "filesystem": {"name": "fs"}, "browser_provider": {"name": "puppeteer", "options": {"headless": false}}, "html_parse_provider": {"name": "cheerio"}}}", "file_path": "src/Storage/99803007-52a0-11ef-86b9-0242ac120003/2024/8/20/", "ecommerce_id": "564dcda8-52a0-11ef-86b9-0242ac120003", "extraction_id": "99803007-52a0-11ef-86b9-0242ac120003", "reference_date": "2024-08-20", "subcategory_id": "0f1ff8d3-52a0-11ef-86b9-0242ac120003", "send_to_process": 1, "extraction_success": 0, "extraction_type_id": "29df6f23-52a0-11ef-86b9-0242ac120003", "extraction_type_name": "subcategoria"}',
        'output' => null,
        'created_at' => '2024-08-20'
      ],
    ]);
    $dailyExtractionFromDatabase = new DailyExtractionModel()->firstWhere('id', 'e27f1d4c-cf62-4b8e-b9c2-688f3f49314f')->toArray();

    $dailyExtractionEntity = new DailyExtractionEntity(
      id: new ValueObjectUuid($dailyExtractionFromDatabase['id']),
      extraction_id: new ValueObjectUuid($dailyExtractionFromDatabase['extraction_id']),
      extraction_success: $dailyExtractionFromDatabase['extraction_success'],
      reference_date: new DateTime($dailyExtractionFromDatabase['reference_date']),
      send_to_process: $dailyExtractionFromDatabase['send_to_process'],
      input: $dailyExtractionFromDatabase['input'],
    );

    $output = 'dados da extracao com produtos';
    $extraction_success = true;

    $dailyExtractionEntity->update(
      extraction_success: $extraction_success,
      output: 'dados da extracao com produtos'
    );

    $response = $this->dailyExtractionRepository->update($dailyExtractionEntity);

    $this->assertTrue($response->extraction_success);
    $this->assertEquals($output, $response->output);

    $this->assertDatabaseHas('daily_extractions', [
      'id' => 'e27f1d4c-cf62-4b8e-b9c2-688f3f49314f',
      'extraction_id' => '99803007-52a0-11ef-86b9-0242ac120003',
      'reference_date' => '2024-08-20',
      'send_to_process' => 1,
      'extraction_success' => $extraction_success,
      'input' => '{"settings": "{"url": "https://servicespub.prod.api.aws.grupokabum.com.br/catalog/v2/products-by-category/hardware/memoria-ram?page_number={}&page_size=100&facet_filters=eyJrYWJ1bV9wcm9kdWN0IjpbInRydWUiXX0%3D&sort=price", "store": {"id": "564dcda8-52a0-11ef-86b9-0242ac120003", "name": "kabum", "filesystem": {"name": "fs"}, "browser_provider": {"name": "puppeteer", "options": {"headless": false}}, "html_parse_provider": {"name": "cheerio"}}}", "file_path": "src/Storage/99803007-52a0-11ef-86b9-0242ac120003/2024/8/20/", "ecommerce_id": "564dcda8-52a0-11ef-86b9-0242ac120003", "extraction_id": "99803007-52a0-11ef-86b9-0242ac120003", "reference_date": "2024-08-20", "subcategory_id": "0f1ff8d3-52a0-11ef-86b9-0242ac120003", "send_to_process": 1, "extraction_success": 0, "extraction_type_id": "29df6f23-52a0-11ef-86b9-0242ac120003", "extraction_type_name": "subcategoria"}',
      'output' => $output,
      'created_at' => '2024-08-20'
    ]);
  }

}