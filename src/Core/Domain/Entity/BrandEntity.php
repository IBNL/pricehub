<?php

namespace Core\Domain\Entity;

use Core\Domain\Factory\BrandEntityValidatorFactory;
use Core\Domain\Notification\NotificationException;
use Core\Domain\ValueObject\ValueObjectUuid;
use DateTime;


class BrandEntity extends BaseEntity
{

  public function __construct(
    protected string $name,
    protected ?string $logo = '',
    protected ?ValueObjectUuid $id = null,
    protected ?DateTime $created_at = null,
  ) {
    parent::__construct();

    $this->logo = $this->logo ?? '';

    $this->id = $this->id ?? ValueObjectUuid::create();

    $this->created_at = $this->created_at ?? new DateTime();

    $this->validation();
  }

  protected function validation()
  {
    BrandEntityValidatorFactory::create()->validate($this);

    if ($this->notification->hasErrors()) {
      throw new NotificationException(
        $this->notification->messages('brandEntity')
      );
    }
  }

}