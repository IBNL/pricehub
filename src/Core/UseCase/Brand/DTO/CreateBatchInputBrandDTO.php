<?php

namespace Core\UseCase\Brand\DTO;


class CreateBatchInputBrandDTO
{
  public function __construct(
    public array $data,
  ){}
}