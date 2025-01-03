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
}
