<?php

namespace Tests\Feature\Core\UseCase\Authenticate;

use App\Models\User as UserModel;
use App\Repositories\Eloquent\UserEloquentRepository;
use App\Services\Authenticate\SanctumService;
use Core\Domain\Exception\UnauthorizedException;
use Core\UseCase\Authenticate\CreateAuthenticateTokenUseCase;
use Core\UseCase\Authenticate\DTO\CreateAuthenticateTokenInputDTO;
use Core\UseCase\Authenticate\DTO\CreateAuthenticateTokenOutputDTO;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class CreateAuthenticateTokenUseCaseTest extends TestCase
{

  public function test_create(): void
  {
    // arrange
    $email = 'jevon@gmail.net';
    $password = 'P4ssword@';

    UserModel::insert([
      ['uuid' => 'e27f1d4c-cf62-4b8e-b9c2-688f3f49314f', 'name' => 'Mr. Darryl Lehner DVM', 'email' => $email, 'password' => Hash::make($password)],
    ]);

    $userModel = new UserModel();
    $userEloquentRepository = new UserEloquentRepository(model: $userModel);

    $authenticateProvider = new SanctumService();

    $useCase = new CreateAuthenticateTokenUseCase(
      userRepository: $userEloquentRepository,
      authenticateProvider: $authenticateProvider
    );

    // action
    $response = $useCase->execute(
      input: new CreateAuthenticateTokenInputDTO(
        email: $email,
        password: $password
      )
    );

    // assert
    $this->assertInstanceOf(CreateAuthenticateTokenOutputDTO::class, $response);
    $this->assertNotEmpty($response->type);
    $this->assertNotEmpty($response->token);
    $this->assertDatabaseCount('personal_access_tokens', 1);

  }

  public function test_unauthorized_exception(): void
  {
    $this->expectException(UnauthorizedException::class);

    // arrange
    $email = 'jevon@gmail.net';
    $password = 'P4ssword@';

    $userModel = new UserModel();
    $userEloquentRepository = new UserEloquentRepository(model: $userModel);

    $authenticateProvider = new SanctumService();

    $useCase = new CreateAuthenticateTokenUseCase(
      userRepository: $userEloquentRepository,
      authenticateProvider: $authenticateProvider
    );

    // action
    $useCase->execute(
      input: new CreateAuthenticateTokenInputDTO(
        email: $email,
        password: $password
      )
    );

  }
}
