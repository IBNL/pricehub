<?php

namespace Core\Domain\Repository;

interface ExtractionRepositoryInterface
{

  public function createDailyExtraction(): array;

}