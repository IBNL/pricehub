<?php

namespace Core\Domain\Repository;

interface ProductRepositoryInterface
{

  public function index(array $filterData = null): array|null;
  public function insertBatch(array $data): array;
  public function updateBatch(array $data): void;

}