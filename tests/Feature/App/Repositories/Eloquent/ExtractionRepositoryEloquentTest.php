<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\ExtractionModel;
use App\Repositories\Eloquent\ExtractionEloquentRepository;
use Core\Domain\Repository\ExtractionRepositoryInterface;
use DateTime;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class ExtractionRepositoryEloquentTest extends TestCase
{
  protected $repository;

  protected function setUp(): void
  {
    parent::setUp();
    Artisan::call(command: 'db:seed');
    $this->repository = new ExtractionEloquentRepository(new ExtractionModel());
  }

  public function testCheckImplementsInterfaceRepository()
  {
    $this->assertInstanceOf(ExtractionRepositoryInterface::class, $this->repository);
  }

  public function test_create_daily_extraction(): void
  {
    // arrange

    // action
    $response = $this->repository->createDailyExtraction();

    // assert
    foreach ($response as $item) {
      $this->assertArrayHasKey('ecommerce_id', $item);
      $this->assertArrayHasKey('extraction_id', $item);
      $this->assertArrayHasKey('subcategory_id', $item);
      $this->assertArrayHasKey('extraction_type_id', $item);
      $this->assertArrayHasKey('extraction_type_name', $item);
      $this->assertArrayHasKey('reference_date', $item);
      $this->assertArrayHasKey('send_to_process', $item);
      $this->assertArrayHasKey('extraction_success', $item);
      $this->assertArrayHasKey('settings', $item);
      $this->assertTrue(boolval($item['send_to_process']));
      $this->assertFalse(boolval($item['extraction_success']));
      $this->assertEquals(new DateTime()->format('Y-m-d'), $item['reference_date']);
      $this->assertNotEmpty($item['ecommerce_id']);
      $this->assertNotEmpty($item['extraction_id']);
      $this->assertNotEmpty($item['subcategory_id']);
      $this->assertNotEmpty($item['extraction_type_id']);
      $this->assertNotEmpty($item['extraction_type_name']);
      $this->assertNotEmpty($item['settings']);
    }

  }
}
