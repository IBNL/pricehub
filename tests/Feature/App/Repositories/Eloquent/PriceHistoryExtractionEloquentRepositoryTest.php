<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\PriceHistoryExtractionModel;
use App\Repositories\Eloquent\PriceHistoryExtractionEloquentRepository;
use Core\Domain\Entity\PriceHistoryExtractionEntity;
use Core\Domain\Repository\PriceHistoryExtractionRepositoryInterface;
use Core\Domain\ValueObject\ValueObjectUuid;
use DateTime;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class PriceHistoryExtractionEloquentRepositoryTest extends TestCase
{
  protected $repository;

  protected function setUp(): void
  {
    parent::setUp();
    $seeders = [
      'EcommerceSeeder',
      'BrandSeeder',
      'ExtractionTypesSeeder',
      'CategoriesSeeder',
      'SubcategoriesSeeder',
      'ProductSeeder',
      'ExtractionSeeder',
    ];

    foreach ($seeders as $seeder) {
      Artisan::call('db:seed', ['--class' => $seeder]);
    }

    $this->repository = new PriceHistoryExtractionEloquentRepository(new PriceHistoryExtractionModel());
  }

  public function testCheckImplementsInterfaceRepository()
  {
    $this->assertInstanceOf(PriceHistoryExtractionRepositoryInterface::class, $this->repository);
  }
  public function testInsertBatch()
  {
    // arrange
    $priceHistoryExtractions = [
      [
        'extraction_id' => '199eddc9-868f-11ef-830e-0242ac120002',
        'product_id' => '564dcda8-52a0-11ef-86b9-0242ac120003',
        'reference_date' => new DateTime(),
        'price' => 10
      ],
      [
        'extraction_id' => '0ccaf743-872a-11ef-ab2a-0242ac120003',
        'product_id' => '0031f304-5cb7-46c9-b238-7fd82a9d7b56',
        'reference_date' => new DateTime(),
        'price' => 1500.99
      ],
      [
        'extraction_id' => '0ae34de8-8c0e-11ef-b927-0242ac120002',
        'product_id' => '0082ac9f-1c0d-41f0-97f8-af277788e381',
        'reference_date' => new DateTime(),
        'price' => 999.99
      ],
    ];

    $priceHistoryExtractionEntities = [];

    foreach ($priceHistoryExtractions as $priceHistoryExtraction) {
      $priceHistoryExtractionEntity = new PriceHistoryExtractionEntity(
        extraction_id: new ValueObjectUuid($priceHistoryExtraction['extraction_id']),
        product_id: new ValueObjectUuid($priceHistoryExtraction['product_id']),
        reference_date: $priceHistoryExtraction['reference_date'],
        price: $priceHistoryExtraction['price'],

      );
      array_push($priceHistoryExtractionEntities, $priceHistoryExtractionEntity);
    }

    //action
    $this->repository->insertBatch($priceHistoryExtractionEntities);

    //assert
    $this->assertDatabaseCount('price_history_extractions', 3);
  }

  #[DataProvider('dataProviderIndex')]
  public function test_index(
    array $filterData
  ): void {

    //arrange
    PriceHistoryExtractionModel::insert([
      [
        'id' => 'e27f1d4c-cf62-4b8e-b9c2-688f3f49314f',
        'extraction_id' => '199eddc9-868f-11ef-830e-0242ac120002',
        'product_id' => '564dcda8-52a0-11ef-86b9-0242ac120003',
        'reference_date' => new DateTime()->format('Y-m-d'),
        'price' => 10,
      ],
      [
        'id' => '31f41cf4-e3c9-4ed9-b0c3-d7aafba76ff4',
        'extraction_id' => '0ccaf743-872a-11ef-ab2a-0242ac120003',
        'product_id' => '0031f304-5cb7-46c9-b238-7fd82a9d7b56',
        'reference_date' => new DateTime()->format('Y-m-d'),
        'price' => 1500.99
      ],
      [
        'id' => '0546cf92-4f3a-44a5-9c6e-351e8a6df4a3',
        'extraction_id' => '0ae34de8-8c0e-11ef-b927-0242ac120002',
        'product_id' => '0082ac9f-1c0d-41f0-97f8-af277788e381',
        'reference_date' => new DateTime()->format('Y-m-d'),
        'price' => 999.99
      ]
    ]);

    // action
    $response = $this->repository->index($filterData['value']);

    // assert
    $this->assertCount($filterData['expectedCount'], $response);
  }

  public static function dataProviderIndex(): array
  {
    return [
      'filter by reference_date' => [
        'filterData' => [
          'value' => [
            'start_reference_date' => new DateTime()->format('Y-m-d'),
            'end_reference_date' => new DateTime()->format('Y-m-d')
          ],
          'expectedCount' => 3,
        ],
      ],
      'filter by 1 product_id' => [
        'filterData' => [
          'value' => [
            'product_id' => ['0082ac9f-1c0d-41f0-97f8-af277788e381']
          ],
          'expectedCount' => 1,
        ],
      ],
      'filter by 2 product_id' => [
        'filterData' => [
          'value' => [
            'product_id' => ['0082ac9f-1c0d-41f0-97f8-af277788e381', '0031f304-5cb7-46c9-b238-7fd82a9d7b56']
          ],
          'expectedCount' => 2,
        ]
      ],
      'empty filter' => [
        'filterData' => [
          'value' => [],
          'expectedCount' => 3
        ],
      ],
      'filter product_id not exist' => [
        'filterData' => [
          'value' => [
            'product_id' => ['0ae34de8-8c0e-11ef-b927-0242ac120002']
          ],
          'expectedCount' => 0,
        ],
      ],
      'filter reference_date not exist' => [
        'filterData' => [
          'value' => [
            'start_reference_date' => new DateTime()->modify('-1 day'),
            'end_reference_date' => new DateTime()->modify('-1 day')
          ],
          'expectedCount' => 0,
        ],
      ],
    ];
  }
}
