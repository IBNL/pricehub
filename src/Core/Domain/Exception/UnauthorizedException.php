<?php

namespace Core\Domain\Exception;

use Exception;

class UnauthorizedException extends Exception
{
  public function __construct()
  {
      parent::__construct(message:"These credentials do not match our records.");
  }
}
