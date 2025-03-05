<?php

namespace Core\Domain\Entity;

use Core\Domain\Notification\Notification;
use Exception;


abstract class BaseEntity
{
  protected $notification;


  public function __construct()
  {
    $this->notification = new Notification();
  }




  public function __get($property)
  {
    // property_exists: retorna true se a propriedade existe no objeto, independentemente do seu valor (incluindo se o valor for null).
    // isset: retorna true apenas se a variável estiver definida e seu valor não for null    
    if (property_exists($this, $property)) {
      return $this->{$property};
    }

    $className = get_class($this);
    throw new Exception("Property {$property} not found in class {$className}");
  }

  public function id(): string
  {
    return (string) $this->id;
  }

  public function createdAt(): string
  {
    return $this->created_at->format('Y-m-d H:i:s');
  }
}