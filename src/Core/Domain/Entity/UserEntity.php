<?php

namespace Core\Domain\Entity;

use Core\Domain\Factory\ValidatorFactory;
use Core\Domain\Notification\NotificationException;
use Core\Domain\Validation\UserEntityLaravelValidator;
use Core\Domain\ValueObject\ValueObjectUuid;
use DateTime;

class UserEntity extends BaseEntity
{

  public function __construct(
    protected string $name,
    protected string $email,
    protected string $password,
    protected ?ValueObjectUuid $uuid = null,
    protected ?int $id = null,
    protected ?DateTime $created_at = null,
  ) {
    parent::__construct();

    $this->uuid = $this->uuid ?? ValueObjectUuid::create();

    $this->created_at = $this->created_at ?? new DateTime();

    $this->validation();
  }

  public function uuid(): string
  {
    return (string) $this->uuid;
  }

  protected function validation()
  {
    $validator = ValidatorFactory::create(UserEntityLaravelValidator::class);
    $validator->validate($this);

    if ($this->notification->hasErrors()) {
      throw new NotificationException(
        $this->notification->messages('UserEntity')
      );
    }
  }
}