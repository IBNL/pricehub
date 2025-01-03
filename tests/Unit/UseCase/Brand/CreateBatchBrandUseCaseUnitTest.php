<?php

namespace Tests\Unit\UseCase\Brand;

use Core\Domain\Entity\BrandEntity;
use Core\Domain\Repository\BrandRepositoryInterface;
use Core\UseCase\Brand\CreateBatchBrandUseCase;
use Core\UseCase\Brand\DTO\CreateBatchInputBrandDTO;
use Core\UseCase\Brand\DTO\CreateBatchOutputBrandDTO;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

class CreateBatchBrandUseCaseUnitTest extends TestCase
{

  public function test_create(): void
  {
    //arrange
    $brands = [
      ['name' => 'BrandName1', 'logo' => 'logo1.png'],
      ['name' => 'BrandName2', 'logo' => 'logo2.png'],
      ['name' => 'BrandName3', 'logo' => ''],
    ];

    $mockEntities = array_map(function ($brand) {
      return Mockery::mock(BrandEntity::class, [
        'name' => $brand['name'],
        'logo' => $brand['logo'],
      ]);
    }, $brands);

    $mockBrandRepository = Mockery::mock(stdClass::class, BrandRepositoryInterface::class);
    $mockBrandRepository->shouldReceive('insertBatch')
      ->once()
      ->andReturn($mockEntities);


    $useCase = new CreateBatchBrandUseCase(
      brandRepository: $mockBrandRepository
    );

    $mockCreateInputBrandDTO = Mockery::mock(CreateBatchInputBrandDTO::class, [$brands]);

    //action
    $response = $useCase->execute(input: $mockCreateInputBrandDTO);

    //assert
    $this->assertInstanceOf(CreateBatchOutputBrandDTO::class, $response);
    $this->assertCount(3, $response->data);

    Mockery::close();
  }
}
