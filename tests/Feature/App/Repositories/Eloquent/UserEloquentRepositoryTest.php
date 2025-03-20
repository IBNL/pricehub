<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\User as UserModel;
use App\Repositories\Eloquent\UserEloquentRepository;
use Core\Domain\Entity\UserEntity;
use Core\Domain\Repository\UserRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class UserEloquentRepositoryTest extends TestCase
{

  protected $repository;

  protected function setUp(): void
  {
    parent::setUp();

    $this->repository = new UserEloquentRepository(new UserModel());
  }

  public function testCheckImplementsInterfaceRepository()
  {
    $this->assertInstanceOf(UserRepositoryInterface::class, $this->repository);
  }

  #[DataProvider('dataProviderFindByColumn')]
  public function testFindByColumn(array $filterData): void
  {
    UserModel::insert([
      ['uuid' => 'e27f1d4c-cf62-4b8e-b9c2-688f3f49314f', 'name' => 'Mr. Darryl Lehner DVM', 'email' => 'jevon@gmail.net', 'password' => 'P4ssword@'],
      ['uuid' => '31f41cf4-e3c9-4ed9-b0c3-d7aafba76ff4', 'name' => 'Jorge Augusto', 'email' => 'jorge@gmail.com', 'password' => 'P4ssword@'],

    ]);

    $response = $this->repository->findByColum(column: $filterData['column'], value: $filterData['value']);

    $this->assertInstanceOf(UserEntity::class, $response);


  }

  public static function dataProviderFindByColumn(): array
  {
    return [
      'FindByColumn by email' => [
        'filterData' => [
          'column' => 'email',
          'value' => 'jevon@gmail.net',
        ]
      ],
      'FindByColumn by name' => [
        'filterData' => [
          'column' => 'name',
          'value' => 'Jorge Augusto',
        ]
      ]
    ];
  }
}
