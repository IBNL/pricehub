<?php

namespace Tests\Feature\Api\DailyExtraction;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class CreateDailyExtractionApiTest extends TestCase
{
  public function setUp(): void
  {
    parent::setUp();
  }

  protected $endpoint = "api/v1/daily-extraction/";

  public function test_create_without_data_in_table_extractions(): void
  {
    // arrange

    // action
    $response = $this->post(uri: $this->endpoint);

    // assert
    $response->assertStatus(Response::HTTP_OK);
    $response->isEmpty();
  }

  public function test_create_with_data_in_table_extractions(): void
  {
    // arrange
    Artisan::call(command: 'db:seed');
  
    // action
    $response = $this->post(uri: $this->endpoint);
    
    // assert
    $response->assertStatus(Response::HTTP_CREATED);
  }
}
