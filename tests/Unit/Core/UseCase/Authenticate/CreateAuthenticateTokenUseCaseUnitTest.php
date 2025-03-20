<?php

namespace Tests\Unit\Core\UseCase\Authenticate;

use Core\Domain\Entity\UserEntity;
use Core\Domain\Repository\UserRepositoryInterface;
use Core\Domain\Services\Authenticate\AuthenticateInterface;
use Core\UseCase\Authenticate\CreateAuthenticateTokenUseCase;
use Core\UseCase\Authenticate\DTO\CreateAuthenticateTokenInputDTO;
use Core\UseCase\Authenticate\DTO\CreateAuthenticateTokenOutputDTO;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

class CreateAuthenticateTokenUseCaseUnitTest extends TestCase
{

  public function test_create(): void
  {
    $name = 'novo nome';
    $email = 'teste@gmail.com';
    $password = 'P4ssword@';

    $mockUserEntity = Mockery::mock(UserEntity::class, [
      'name' => $name,
      'email' => $email,
      'password' => $password
    ]);


    $mockUserRepository = Mockery::mock(stdClass::class, UserRepositoryInterface::class);
    $mockUserRepository->shouldReceive('findByColum')
      ->once()
      ->andReturn($mockUserEntity);

    $mockAuthenticateProvider = Mockery::mock(stdClass::class, AuthenticateInterface::class);
    $mockAuthenticateProvider->shouldReceive('validateCredentials')
      ->once()
      ->andReturn(true);

    $mockAuthenticateProvider->shouldReceive('createAuthenticateToken')
      ->once()
      ->andReturn('token_jwt');

    $mockAuthenticateProvider->shouldReceive('getTypeToken')
      ->once()
      ->andReturn('Bearer');

    $useCase = new CreateAuthenticateTokenUseCase(
      userRepository: $mockUserRepository,
      authenticateProvider: $mockAuthenticateProvider
    );


    $mockCreateAuthenticateTokenInputDTO = Mockery::mock(CreateAuthenticateTokenInputDTO::class, [
      $email,
      $password
    ]);

    $mockCreateAuthenticateTokenInputDTO->shouldReceive('toArray')
      ->once()
      ->andReturn(['email' => $email, 'password' => $password]);

    $response = $useCase->execute(
      input: $mockCreateAuthenticateTokenInputDTO
    );

    $this->assertInstanceOf(CreateAuthenticateTokenOutputDTO::class, $response);
  }
}
