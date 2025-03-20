<?php

namespace Core\Domain\Repository;

use Core\Domain\Entity\UserEntity;

interface UserRepositoryInterface
{

  public function findByColum(string $column, string $value): UserEntity|null;
  

}