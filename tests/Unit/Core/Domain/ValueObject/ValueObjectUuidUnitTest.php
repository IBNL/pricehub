<?php

namespace Tests\Unit\Core\Domain\ValueObject;

use Core\Domain\ValueObject\ValueObjectUuid;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;

class ValueObjectUuidUnitTest extends TestCase
{

  /**
   * Testa se a classe Uuid gera um UUID válido usando o método random().
   *
   * @return void
   */
  public function testRandomGeneratesValidUuid()
  {
    $uuid = ValueObjectUuid::create();

    // Verifica se o valor gerado é uma instância válida de UUID
    $this->assertTrue(RamseyUuid::isValid($uuid->__toString()));
  }

  /**
   * Testa se a classe Uuid aceita um UUID válido.
   *
   * @return void
   */
  public function testEnsureIsValidAcceptsValidUuid()
  {
    // Um UUID válido gerado pela biblioteca Ramsey
    $validUuid = RamseyUuid::uuid4()->toString();

    $uuid = new ValueObjectUuid($validUuid);

    $this->assertInstanceOf(ValueObjectUuid::class, $uuid);
    $this->assertEquals($validUuid, (string) $uuid);
  }

  /**
   * Testa se a classe Uuid lança uma exceção ao tentar instanciar um UUID inválido.
   *
   * @return void
   */
  public function testEnsureIsValidThrowsExceptionOnInvalidUuid()
  {
    // Passa um UUID inválido para garantir que a exceção seja lançada
    $this->expectException(InvalidArgumentException::class);

    new ValueObjectUuid('invalid-uuid');
  }
}
