<?php

namespace Core\Domain\ValueObject;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid as RamseyUuid;

class ValueObjectUuid
{
  public function __construct(
    protected string $value
  ) {
    $this->ensureIsValid($value);
  }

  public static function create(): ValueObjectUuid
  {
    return new self(RamseyUuid::uuid4()->toString());
  }

  public function __toString(): string
  {
    return $this->value;
  }

  private function ensureIsValid(string $id): void
  {
    if (!RamseyUuid::isValid($id)) {
      throw new InvalidArgumentException(sprintf('<%s> does not allow the value <%s>.', static::class, $id));
    }
  }
}