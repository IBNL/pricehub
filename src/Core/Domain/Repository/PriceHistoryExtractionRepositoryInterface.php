<?php

namespace Core\Domain\Repository;

interface PriceHistoryExtractionRepositoryInterface
{ 

  public function insertBatch(array $data): array;
  //public function index(array $filterData = []): array;

}