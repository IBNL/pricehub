<?php

namespace Core\UseCase\Authenticate;

use Core\Domain\Exception\UnauthorizedException;
use Core\Domain\Repository\UserRepositoryInterface;
use Core\Domain\Services\Authenticate\AuthenticateInterface;
use Core\UseCase\Authenticate\DTO\CreateAuthenticateTokenInputDTO;
use Core\UseCase\Authenticate\DTO\CreateAuthenticateTokenOutputDTO;

class CreateAuthenticateTokenUseCase
{
  public function __construct(
    protected UserRepositoryInterface $userRepository,
    protected AuthenticateInterface $authenticateProvider

  ) {
  }

  public function execute(CreateAuthenticateTokenInputDTO $input): CreateAuthenticateTokenOutputDTO
  {
    $validCredentials = $this->authenticateProvider->validateCredentials(credentials: $input->toArray());

    if (!$validCredentials) {
      throw new UnauthorizedException();
    }

    $user = $this->userRepository->findByColum('email', $input->email);

    $token = $this->authenticateProvider->createAuthenticateToken($user);

    $type = $this->authenticateProvider->getTypeToken();

    return new CreateAuthenticateTokenOutputDTO(
      type: $type,
      token: $token
    );
  }

}