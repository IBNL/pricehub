<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\BrandModel;
use App\Repositories\Eloquent\BrandEloquentRepository;
use Core\Domain\Entity\BrandEntity;
use Core\Domain\Repository\BrandRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use DB;


class BrandEloquentRepositoryTest extends TestCase
{
  protected $repository;

  protected function setUp(): void
  {
    parent::setUp();

    $this->repository = new BrandEloquentRepository(new BrandModel());
  }

  public function testCheckImplementsInterfaceRepository()
  {
    $this->assertInstanceOf(BrandRepositoryInterface::class, $this->repository);
  }

  public function testInsertBatch()
  {
    //arrange
    $brands = [
      ['name' => 'BrandName1', 'logo' => 'logo1.png'],
      ['name' => 'BrandName2', 'logo' => 'logo2.png'],
      ['name' => 'BrandName3', 'logo' => ''],
    ];

    $brandEntities = [];

    foreach ($brands as $brand) {
      $brandEntity = new BrandEntity(
        name: $brand['name'],
        logo: $brand['logo']
      );
      array_push($brandEntities, $brandEntity);
    }

    //action
    $response = $this->repository->insertBatch($brandEntities);

    //assert
    $this->assertDatabaseCount('brands', 3);

    foreach ($response as $key => $brand) {
      $this->assertEquals($brandEntities[$key]->id, $brand['id']);
      $this->assertEquals($brandEntities[$key]->name, $brand['name']);
      $this->assertEquals($brandEntities[$key]->logo, $brand['logo']);
    }

  }

  public function testGetBrandNeedCreate(): void
  {
    // arrange 
    BrandModel::insert([
      ['id' => 'e27f1d4c-cf62-4b8e-b9c2-688f3f49314f', 'name' => 'BrandNameExist1', 'logo' => 'logoExist1.png', 'deleted_at' => null],
      ['id' => '31f41cf4-e3c9-4ed9-b0c3-d7aafba76ff4', 'name' => 'BrandNameExist2', 'logo' => 'logoExist2.png', 'deleted_at' => null],
      ['id' => '0546cf92-4f3a-44a5-9c6e-351e8a6df4a3', 'name' => 'BrandNameExist3', 'logo' => 'logoExist3.png', 'deleted_at' => null],
      ['id' => 'b53bdb4f-6974-4be0-b54d-cb7e9a7f210d', 'name' => 'BrandNameDeleted', 'logo' => 'logoDeleted1.png', 'deleted_at' => '2024-08-07 23:45:09'],
    ]);

    $brands = [
      ['name' => 'BrandName1', 'logo' => 'logo1.png'],
      ['name' => 'BrandName2', 'logo' => 'logo2.png'],
      ['name' => 'BrandName2', 'logo' => 'logo2.png'],
      ['name' => 'BrandName3', 'logo' => ''],
      ['name' => 'BrandNameExist1', 'logo' => 'logoExist1.png'],
      ['name' => 'BrandNameExist2', 'logo' => 'logoExist2.png'],
      ['name' => 'BrandNameExist3', 'logo' => 'logoExist3.png'],
    ];

    // action 
    $response = $this->repository->getBrandNeedCreate($brands);

    // assert
    $this->assertCount(3, $response);

  }

}
