<?php

namespace Core\UseCase\Product\DTO;


class CreateBatchOutputProductDTO
{
  public function __construct(
    public array $data,
  ){}
}