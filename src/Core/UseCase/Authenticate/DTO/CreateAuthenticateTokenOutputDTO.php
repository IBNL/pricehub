<?php

namespace Core\UseCase\Authenticate\DTO;


class CreateAuthenticateTokenOutputDTO
{
  public function __construct(
    public string $type,
    public string $token,
  ) {
  }

}