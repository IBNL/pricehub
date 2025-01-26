<?php

namespace Core\UseCase\DailyExtraction\DTO;


class CreateBatchOutputDailyExtractionDTO
{
  public function __construct(
    public array $data,
  ){}
}