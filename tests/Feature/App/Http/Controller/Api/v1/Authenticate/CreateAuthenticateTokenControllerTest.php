<?php

namespace Tests\Feature\App\Http\Controller\Api\v1\Authenticate;

use App\Http\Controllers\Api\v1\Authenticate\CreateAuthenticateTokenController;
use App\Http\Requests\Authenticate\CreateAuthenticateTokenRequest;
use App\Repositories\Eloquent\UserEloquentRepository;
use App\Services\Authenticate\SanctumService;
use Core\Domain\Exception\UnauthorizedException;
use Core\Domain\Repository\UserRepositoryInterface;
use Core\Domain\Services\Authenticate\AuthenticateInterface;
use Core\UseCase\Authenticate\CreateAuthenticateTokenUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\ParameterBag;
use Tests\TestCase;
use App\Models\User as UserModel;
use Illuminate\Http\Response;


class CreateAuthenticateTokenControllerTest extends TestCase
{
  protected UserRepositoryInterface $userEloquentRepository;

  protected AuthenticateInterface $sanctumService;

  protected CreateAuthenticateTokenUseCase $useCase;

  protected CreateAuthenticateTokenController $controller;

  protected CreateAuthenticateTokenRequest $request;

  protected function setUp(): void
  {
    $userModel = new UserModel();

    $this->userEloquentRepository = new UserEloquentRepository(
      model: $userModel
    );

    $this->sanctumService = new SanctumService();

    $this->useCase = new CreateAuthenticateTokenUseCase(
      userRepository: $this->userEloquentRepository,
      authenticateProvider: $this->sanctumService
    );

    $this->request = new CreateAuthenticateTokenRequest();
    $this->request->headers->set('content-type', 'application/json');

    $this->controller = new CreateAuthenticateTokenController();

    parent::setUp();
  }
  public function test_create(): void
  {

    $email = 'jevon@gmail.net';
    $password = 'P4ssword@';

    UserModel::insert([
      ['uuid' => 'e27f1d4c-cf62-4b8e-b9c2-688f3f49314f', 'name' => 'Mr. Darryl Lehner DVM', 'email' => $email, 'password' => Hash::make($password)],
    ]);

    $this->request->setJson(
      new ParameterBag([
        'email' => $email,
        'password' => $password,
      ])
    );

    $response = $this->controller->__invoke(
      request: $this->request,
      createAuthenticateTokenUseCase: $this->useCase
    );

    $this->assertInstanceOf(JsonResponse::class, $response);
    $this->assertEquals(Response::HTTP_OK, $response->status());
    $this->assertDatabaseCount('personal_access_tokens', 1);

  }

  public function test_unauthorized_exception(): void
  {
    $this->expectException(UnauthorizedException::class);

    $email = 'jevon@gmail.net';
    $password = 'P4ssword@';

    $this->request->setJson(
      new ParameterBag([
        'email' => $email,
        'password' => $password,
      ])
    );

    $this->controller->__invoke(
      request: $this->request,
      createAuthenticateTokenUseCase: $this->useCase
    );

  }
}
