<?php

namespace Core\Domain\Repository;

use Core\Domain\Entity\DailyExtractionEntity;

interface DailyExtractionInterface
{
  ## ADD Repository no nome
  public function insertBatch(array $data): array;
  public function findByColumns(array $columns): DailyExtractionEntity|null;

  public function update(DailyExtractionEntity $dailyExtractionEntity): DailyExtractionEntity;


}