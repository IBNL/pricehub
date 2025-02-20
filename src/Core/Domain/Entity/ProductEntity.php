<?php

namespace Core\Domain\Entity;

use Core\Domain\Factory\ValidatorFactory;
use Core\Domain\Notification\NotificationException;
use Core\Domain\Validation\ProductEntityLaravelValidator;
use Core\Domain\ValueObject\ValueObjectUuid;
use DateTime;


class ProductEntity extends BaseEntity
{
  public function __construct(
    protected bool $is_active,
    protected string $url,
    protected string $name,
    protected bool $available,
    protected ?string $slug = null,
    protected ?ValueObjectUuid $ecommerce_id = null,
    protected ?ValueObjectUuid $brand_id = null,
    protected ?ValueObjectUuid $subcategory_id = null,
    protected ?DateTime $last_date_price = null,
    protected ?float $last_price = null,
    protected ?string $logo_from_ecommerce = null,
    protected ?string $logo = null,
    protected ?ValueObjectUuid $id = null,
    protected ?DateTime $created_at = null
  ) {
    parent::__construct();

    #$this->ecommerce_id = $this->ecommerce_id ?? '';

    #$this->brand_id = $this->brand_id ?? '';

    #$this->subcategory_id = $this->subcategory_id ?? '';

    #$this->logo_from_ecommerce = $this->logo_from_ecommerce ?? '';

    #$this->logo = $this->logo ?? '';

    $this->id = $this->id ?? ValueObjectUuid::create();

    $this->created_at = $this->created_at ?? new DateTime();

    $this->validation();
  }

  public function update(
    bool $available,
    bool $is_active,
    DateTime $last_date_price,
    float $last_price,
  ): void 
  {
    $this->available = $available;
    $this->is_active = $is_active;
    $this->last_date_price = $last_date_price;
    $this->last_price = $last_price;
    $this->validation();
  }

  protected function validation()
  {
    $validator = ValidatorFactory::create(ProductEntityLaravelValidator::class);
    $validator->validate($this);

    if ($this->notification->hasErrors()) {
      throw new NotificationException(
        $this->notification->messages('ProductEntity')
      );
    }
  }
}