<?php

namespace Tests\Unit\Core\Domain\Entity;

use Core\Domain\Entity\UserEntity;
use Core\Domain\Notification\NotificationException;
use Tests\TestCase;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\DataProvider;


class UserEntityUnitTest extends TestCase
{

  #[DataProvider('dataProviderCreate')]
  public function test_create(string $name, string $email, string $password): void
  {
    $userEntity = new UserEntity(
      name: $name,
      email: $email,
      password: $password
    );

    $this->assertTrue(Str::isUuid($userEntity->uuid()));
    $this->assertEquals($name, $userEntity->name);
    $this->assertEquals($email, $userEntity->email);
    $this->assertEquals($password, $userEntity->password);

    $this->assertNotEmpty($userEntity->createdAt());
  }

  public static function dataProviderCreate(): array
  {
    return [
      'valid name,email and password' => [
        'name' => 'novo nome',
        'email' => 'meuemail@gmail.com',
        'password' => 'P4ssword@'
      ],
    ];
  }

  #[DataProvider('dataProviderValidator')]
  public function testValidator(string $name, string $email, $password): void
  {
    $this->expectException(NotificationException::class);

    new UserEntity(
      name: $name,
      email: $email,
      password: $password
    );
  }

  public static function dataProviderValidator(): array
  {
    return [
      'Senha sem caracteres maiúsculos' => [
        'name' => 'Maria Oliveira',
        'email' => 'maria.oliveira@exemplo.com',
        'password' => 'senha123!'
      ],
      'Senha sem caracteres especiais' => [
        'name' => 'Carlos Santos',
        'email' => 'carlos.santos@exemplo.com',
        'password' => 'Senha123'
      ],
      'Senha sem números' => [
        'name' => 'Fernanda Costa',
        'email' => 'fernanda.costa@exemplo.com',
        'password' => 'SenhaForte!'
      ],
      'Senha menor que 8 caracteres' => [
        'name' => 'Fernanda Costa',
        'email' => 'fernanda.costa@exemplo.com',
        'password' => 'SenhaForte!'
      ],
      'Senha sem caracteres minúsculas' => [
        'name' => 'Ana Lima',
        'email' => 'ana.lima@exemplo.com',
        'password' => 'SENHA123!',
      ],
      'name vazio com senha valida' => [
        'name' => '',
        'email' => 'ana.lima@exemplo.com',
        'password' => 'Senha123!',
      ],
      'email vazio com senha valida' => [
        'name' => 'Ana lima',
        'email' => '',
        'password' => 'Senha123!',
      ],
      'email e name vazio com senha valida' => [
        'name' => '',
        'email' => '',
        'password' => 'Senha123!',
      ],
      'name e senha valida com email invalido' => [
        'name' => 'Ana lima',
        'email' => 'ana.com.br',
        'password' => 'Senha123!',
      ],
    ];
  }
}
