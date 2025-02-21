<?php

namespace Core\UseCase\Product\DTO;

use DateTime;


class ProductProcessingInputDto
{
  public function __construct(
    public string $data,
  ) {
  }
}
