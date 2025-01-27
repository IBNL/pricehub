<?php

namespace Core\Domain\Repository;

interface BrandRepositoryInterface
{ 

  public function insertBatch(array $data): array;
  public function getBrandNeedCreate(array $brands): array;
  public function index(array $filterData = []): array;

}