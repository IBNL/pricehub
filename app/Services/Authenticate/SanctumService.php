<?php

namespace App\Services\Authenticate;

use Auth;
use Core\Domain\Entity\UserEntity;
use App\Models\User as UserModel;
use Core\Domain\Services\Authenticate\AuthenticateInterface;

class SanctumService implements AuthenticateInterface
{

  public function validateCredentials(array $credentials): bool
  {
    return Auth::attempt(credentials: $credentials);
  }

  public function createAuthenticateToken(UserEntity $user): string
  {
    $userModel = UserModel::find(id: $user->id);

    return $userModel->createToken(name: 'access_token')->plainTextToken;
  }

  public function getTypeToken(): string
  {
    return 'Bearer';
  }

}