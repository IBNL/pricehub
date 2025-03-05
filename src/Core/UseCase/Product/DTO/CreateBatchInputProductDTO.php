<?php

namespace Core\UseCase\Product\DTO;


class CreateBatchInputProductDTO
{
  public function __construct(
    public array $data,
  ){}
}