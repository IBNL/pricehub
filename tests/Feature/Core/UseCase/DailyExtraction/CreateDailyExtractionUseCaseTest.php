<?php

namespace Tests\Feature\Core\UseCase\DailyExtraction;

use App\Models\DailyExtractionModel;
use App\Models\ExtractionModel;
use App\Repositories\Eloquent\DailyExtractionEloquentRepository;
use App\Repositories\Eloquent\ExtractionEloquentRepository;
use Core\UseCase\DailyExtraction\CreateDailyExtractionUseCase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class CreateDailyExtractionUseCaseTest extends TestCase
{
  protected function setUp(): void
  {
    parent::setUp();
    Artisan::call('db:seed');
  }

  public function test_create(): void
  {
    // arrange
    $extractionModel = new ExtractionModel();
    $extractionEloquentRepository = new ExtractionEloquentRepository(model: $extractionModel);

    $dailyExtractionModel = new DailyExtractionModel();
    $dailyExtractionEloquentRepository = new DailyExtractionEloquentRepository(model: $dailyExtractionModel);

    $createDailyExtractionUseCase = new CreateDailyExtractionUseCase(
      extractionRepository: $extractionEloquentRepository,
      dailyExtractionRepository: $dailyExtractionEloquentRepository

    );
    // action
    $response = $createDailyExtractionUseCase->execute();

    // assert
    foreach ($response->data as $item) {
      $this->assertArrayHasKey('id', $item);
      $this->assertArrayHasKey('extraction_id', $item);
      $this->assertArrayHasKey('input', $item);
      $this->assertArrayHasKey('output', $item);
      $this->assertArrayHasKey('extraction_success', $item);
      $this->assertArrayHasKey('reference_date', $item);
      $this->assertArrayHasKey('send_to_process', $item);

      $this->assertNotEmpty($item['extraction_id']);
      $this->assertNotEmpty($item['reference_date']);
      $this->assertNotEmpty($item['input']);
      $this->assertTrue(boolval($item['send_to_process']));
      $this->assertFalse(boolval($item['extraction_success']));
      $this->assertEmpty($item['output']);
    }
  }
}

