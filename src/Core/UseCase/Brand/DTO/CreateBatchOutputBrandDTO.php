<?php

namespace Core\UseCase\Brand\DTO;


class CreateBatchOutputBrandDTO
{
  public function __construct(
    public array $data,
  ){}
}