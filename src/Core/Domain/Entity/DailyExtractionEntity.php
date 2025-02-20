<?php

namespace Core\Domain\Entity;

use Core\Domain\Factory\DailyExtractionEntityValidatorFactory;
use Core\Domain\Notification\NotificationException;
use Core\Domain\ValueObject\ValueObjectUuid;
use DateTime;

class DailyExtractionEntity extends BaseEntity
{
  public function __construct(
    protected ValueObjectUuid $extraction_id,
    protected DateTime $reference_date,
    protected ?bool $extraction_success = false,
    protected ?bool $send_to_process = false,
    protected ?string $input = null,
    protected ?string $output = null,
    protected ?ValueObjectUuid $id = null,
    protected ?DateTime $created_at = null
  ) {
    parent::__construct();

    #$this->input = $this->input ?? '';

    #$this->output = $this->output ?? '';

    $this->id = $this->id ?? ValueObjectUuid::create();

    $this->created_at = $this->created_at ?? new DateTime();

    
    $this->validation();
  }

  public function update(
    bool $extraction_success,
    string $output
  ): void 
  {
    $this->extraction_success = $extraction_success;
    $this->output = $output;
    $this->validation();
  }

  protected function validation()
  {
    DailyExtractionEntityValidatorFactory::create()->validate($this);

    if ($this->notification->hasErrors()) {
      throw new NotificationException(
        $this->notification->messages('DailyExtractionEntity')
      );
    }
  }

}