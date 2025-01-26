<?php

namespace Tests\Feature\App\Http\Controller\Api\v1\DailyExtraction;

use App\Http\Controllers\Api\v1\DailyExtraction\CreateDailyExtractionController;
use App\Models\DailyExtractionModel;
use App\Models\ExtractionModel;
use App\Repositories\Eloquent\DailyExtractionEloquentRepository;
use App\Repositories\Eloquent\ExtractionEloquentRepository;
use Core\UseCase\DailyExtraction\CreateDailyExtractionUseCase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class CreateDailyExtractionControllerTest extends TestCase
{
  protected CreateDailyExtractionController $controller;
  protected CreateDailyExtractionUseCase $createDailyExtractionUseCase;

  public function setUp(): void
  {
    parent::setUp();

    $this->controller = new CreateDailyExtractionController();

    $extractionModel = new ExtractionModel();
    $extractionEloquentRepository = new ExtractionEloquentRepository(model: $extractionModel);

    $dailyExtractionModel = new DailyExtractionModel();
    $dailyExtractionEloquentRepository = new DailyExtractionEloquentRepository(model: $dailyExtractionModel);

    $this->createDailyExtractionUseCase = new CreateDailyExtractionUseCase(
      extractionRepository: $extractionEloquentRepository,
      dailyExtractionRepository: $dailyExtractionEloquentRepository

    );

  }

  public function test_create_with_seeder_status_code_201(): void
  {
    Artisan::call(command: 'db:seed');
    $response = $this->controller->__invoke(
      createDailyExtractionUseCase: $this->createDailyExtractionUseCase
    );

    $this->assertEquals(Response::HTTP_CREATED, $response->status());
  }

  public function test_create_without_seeder_status_code_200(): void
  {
    $response = $this->controller->__invoke(
      createDailyExtractionUseCase: $this->createDailyExtractionUseCase
    );

    $this->assertEquals(Response::HTTP_OK, $response->status());
  }
}
