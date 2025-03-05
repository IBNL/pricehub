<?php

namespace Core\Domain\Entity;

use Core\Domain\Factory\ValidatorFactory;
use Core\Domain\Notification\NotificationException;
use Core\Domain\Validation\PriceHistoryExtractionEntityLaravelValidator;
use Core\Domain\ValueObject\ValueObjectUuid;
use DateTime;

class PriceHistoryExtractionEntity extends BaseEntity
{
  public function __construct(
    protected ValueObjectUuid $extraction_id,
    protected ValueObjectUuid $product_id,
    protected DateTime $reference_date,
    protected float $price,
    protected ?ValueObjectUuid $id = null,
    protected ?DateTime $created_at = null
  ) {
    parent::__construct();

    $this->id = $this->id ?? ValueObjectUuid::create();

    $this->created_at = $this->created_at ?? new DateTime();

    $this->validation();
  }

  protected function validation()
  {
    $validator = ValidatorFactory::create(PriceHistoryExtractionEntityLaravelValidator::class);
    $validator->validate($this);
    if ($this->notification->hasErrors()) {
      throw new NotificationException(
        $this->notification->messages(context: 'PriceHistoryExtractionEntity')
      );
    }
  }
}