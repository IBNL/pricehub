<?php

namespace App\Repositories\Eloquent;

use App\Models\User as UserModel;
use Core\Domain\Entity\UserEntity;
use Core\Domain\Repository\UserRepositoryInterface;
use Core\Domain\ValueObject\ValueObjectUuid;
use DateTime;


class UserEloquentRepository implements UserRepositoryInterface
{
  public function __construct(
    protected UserModel $model
  ) {
  }

  public function findByColum(string $column, string $value, $returnModel = false): UserEntity|null
  {
    $modelDb = $this->model->where($column, $value)->first();

    $entity = $this->convertToEntity(model: $modelDb);

    return $entity;
  }

  private function convertToEntity(UserModel $model): UserEntity
  {
    $entity = new UserEntity(
      id: $model->id,
      uuid: new ValueObjectUuid($model->uuid),
      name: $model->name,
      email: $model->email,
      password: $model->password,
      created_at: new DateTime($model->created_at)
    );

    return $entity;
  }

}