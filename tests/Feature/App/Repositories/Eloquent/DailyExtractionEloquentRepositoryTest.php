<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\DailyExtractionModel;
use App\Models\ExtractionModel;
use App\Repositories\Eloquent\DailyExtractionEloquentRepository;
use App\Repositories\Eloquent\ExtractionEloquentRepository;
use Core\Domain\Entity\DailyExtractionEntity;
use Core\Domain\Repository\DailyExtractionInterface;
use Core\Domain\ValueObject\ValueObjectUuid;
use DateTime;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

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
    $this->assertInstanceOf(DailyExtractionInterface::class, $this->dailyExtractionRepository);
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
}