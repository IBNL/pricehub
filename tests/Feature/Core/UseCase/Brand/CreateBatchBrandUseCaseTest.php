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
    BrandModel::insert([
      ['id' => 'e27f1d4c-cf62-4b8e-b9c2-688f3f49314f', 'name' => 'BrandNameExist1', 'logo' => 'logoExist1.png', 'deleted_at' => null],
      ['id' => '31f41cf4-e3c9-4ed9-b0c3-d7aafba76ff4', 'name' => 'BrandNameExist2', 'logo' => 'logoExist2.png', 'deleted_at' => null],
      ['id' => '0546cf92-4f3a-44a5-9c6e-351e8a6df4a3', 'name' => 'BrandNameExist3', 'logo' => 'logoExist3.png', 'deleted_at' => null],
      ['id' => 'b53bdb4f-6974-4be0-b54d-cb7e9a7f210d', 'name' => 'BrandNameDeleted', 'logo' => 'logoDeleted1.png', 'deleted_at' => '2024-08-07 23:45:09'],
    ]);

    $brandModel = new BrandModel();

    $brandEloquentRepository = new BrandEloquentRepository(
      model: $brandModel
    );

    $createBatchBrandUseCase = new CreateBatchBrandUseCase(
      brandRepository: $brandEloquentRepository
    );
    // brands com dados duplicados e que ja existem na base
    $brands = [
      ['name' => 'BrandName1', 'logo' => 'logo1.png'],
      ['name' => 'BrandName2', 'logo' => 'logo2.png'],
      ['name' => 'BrandName2', 'logo' => 'logo2.png'],
      ['name' => 'BrandName3', 'logo' => ''],
      ['name' => 'BrandNameExist1', 'logo' => 'logoExist1.png'],
      ['name' => 'BrandNameExist2', 'logo' => 'logoExist2.png'],
      ['name' => 'BrandNameExist3', 'logo' => 'logoExist3.png'],
    ];

    // brands que serão criadas, garantindo que sejam únicas e ainda não existam no banco de dados.
    $brandsCreated = [
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
    $this->assertCount(3, $response->data);
    $this->assertDatabaseCount('brands', 7);

    foreach ($response->data as $key => $brand) {
      $this->assertEquals($brandsCreated[$key]['name'], $brand['name']);
      $this->assertEquals($brandsCreated[$key]['logo'], $brand['logo']);
    }
  }
}
