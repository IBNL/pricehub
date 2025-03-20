<?php

namespace Core\Domain\Services\Authenticate;

use Core\Domain\Entity\UserEntity;


interface AuthenticateInterface
{
  public function validateCredentials(array $credentials): bool;
  public function createAuthenticateToken(UserEntity $user): string;
  public function getTypeToken(): string;

}