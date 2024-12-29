<?php

namespace Core\Domain\Notification;

use InvalidArgumentException;

class Notification
{
  private $errors = [];

  public function getErrors(): array
  {
    return $this->errors;
  }

  /**
   * @param $error array[context, message]
   */
  public function addError(array $error): void
  {
    if (!isset($error['context']) || !isset($error['message'])) {
      throw new InvalidArgumentException("The error must contain 'context' and 'message'.");
    }
    array_push($this->errors, $error);
  }

  public function hasErrors(): bool
  {
    return count($this->errors) > 0;
  }

  public function messages(string $context = ''): string
  {
    $messages = [];

    foreach ($this->errors as $error) {
      if ($context === '' || $error['context'] === $context) {
        $messages[] = "{$error['context']}: {$error['message']}";
      }
    }

    return implode(',', $messages);
  }
}
