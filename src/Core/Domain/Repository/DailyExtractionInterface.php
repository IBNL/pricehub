<?php

namespace Core\Domain\Repository;

interface DailyExtractionInterface
{ 

  public function insertBatch(array $data): array;

}