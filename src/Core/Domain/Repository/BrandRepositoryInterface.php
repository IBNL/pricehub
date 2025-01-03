<?php

namespace Core\Domain\Repository;

interface BrandRepositoryInterface
{ 

  public function insertBatch(array $data): array;

}