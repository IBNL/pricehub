<?php

namespace Tests\Feature\Core\UseCase\Brand;

use App\Models\BrandModel;
use App\Repositories\Eloquent\BrandEloquentRepository;
use Core\UseCase\Brand\CreateBatchBrandUseCase;
use Core\UseCase\Brand\DTO\CreateBatchInputBrandDTO;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateBatchBrandUseCaseTest extends TestCase
{
  public function test_create(): void
  {
    //arrange
    $brandModel = new BrandModel();

    $brandEloquentRepository = new BrandEloquentRepository(
      model: $brandModel
    );

    $createBatchBrandUseCase = new CreateBatchBrandUseCase(
      brandRepository: $brandEloquentRepository
    );

    $brands = [
      ['name' => 'BrandName1', 'logo' => 'logo1.png'],
      ['name' => 'BrandName2', 'logo' => 'logo2.png'],
      ['name' => 'BrandName3', 'logo' => ''],
    ];

    //action
    $response = $createBatchBrandUseCase->execute(
      input: new CreateBatchInputBrandDTO(
        data: $brands
      )
    );

    //assert
    $this->assertDatabaseCount('brands', 3);

    foreach ($response->data as $key => $brand) {
      $this->assertEquals($brands[$key]['name'], $brand['name']);
      $this->assertEquals($brands[$key]['logo'], $brand['logo']);
    }
  }
}
