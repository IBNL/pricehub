<?php

namespace Core\UseCase\Brand;

use Core\Domain\Entity\BrandEntity;
use Core\Domain\Repository\BrandRepositoryInterface;
use Core\UseCase\Brand\DTO\CreateBatchInputBrandDTO;
use Core\UseCase\Brand\DTO\CreateBatchOutputBrandDTO;

class CreateBatchBrandUseCase
{
  public function __construct(
    protected BrandRepositoryInterface $brandRepository
  ) {
  }

  public function execute(CreateBatchInputBrandDTO $input): CreateBatchOutputBrandDTO
  {
    $brandEntities = [];
    foreach ($input->data as $brand) {
      $brandEntity = new BrandEntity(
        name: $brand['name'],
        logo: $brand['logo'] ?? ''
      );
      array_push($brandEntities, $brandEntity);
    }

    $response = $this->brandRepository->insertBatch(data: $brandEntities);

    return new CreateBatchOutputBrandDTO(data: $response);
  }

}