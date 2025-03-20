<?php

namespace Tests\Feature\Api\Authenticate;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User as UserModel;

class CreateAuthenticateTokenApiTest extends TestCase
{
  protected $endpoint = '/api/v1/authenticate/create-token';

  public function test_create(): void
  {

    $email = 'jevon@gmail.net';
    $password = 'P4ssword@';

    UserModel::insert([
      ['uuid' => 'e27f1d4c-cf62-4b8e-b9c2-688f3f49314f', 'name' => 'Mr. Darryl Lehner DVM', 'email' => $email, 'password' => Hash::make($password)],
    ]);

    $data = [
      'email' => $email,
      'password' => $password
    ];
    $response = $this->postJson(
      uri: $this->endpoint,
      data: $data
    );

    $response->assertStatus(Response::HTTP_OK);
    $response->assertJsonStructure([
      'data' => [
        'type',
        'token',
      ],
    ]);

    $this->assertDatabaseCount('personal_access_tokens', 1);
  }

  public function test_unauthorized_exception(): void
  {
    $data = [
      'email' => 'jevon@gmail.net',
      'password' => 'P4ssword@'
    ];
    $response = $this->postJson(
      uri: $this->endpoint,
      data: $data
    );

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    $response->assertJsonStructure([
      'message'
    ]);
  }

  public function test_validations_create()
  {
    $data = [];

    $response = $this->postJson(
      uri: $this->endpoint,
      data: $data
    );

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    $response->assertJsonStructure([
      'message',
      'errors' => [
        'email',
        'password',
      ],
    ]);
  }
}
