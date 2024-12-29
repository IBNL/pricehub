<?php

namespace Tests\Unit\Core\Domain\Notification;

use Core\Domain\Notification\Notification;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class NotificationUnitTest extends TestCase
{
  public function testGetErrors()
  {
    $notification = new Notification();
    $errors = $notification->getErrors();

    $this->assertIsArray($errors);
  }

  public function testAddErrors()
  {
    $notification = new Notification();
    $notification->addError([
      'context' => 'context_example',
      'message' => 'context_example title is required',
    ]);

    $errors = $notification->getErrors();

    $this->assertCount(1, $errors);
  }

  #[DataProvider('dataProviderExceptionAddErrors')]
  public function testExceptionAddErrors(string|null $context, string|null $message)
  {
    $this->expectException(InvalidArgumentException::class);
    $notification = new Notification();
    $notification->addError([
      'context' => $context,
      'message' => $message
    ]);
  }

  public static function dataProviderExceptionAddErrors(): array
  {
    return [
      'test without context' => [
        'context' => null,
        'message' => 'title is required'
      ],
      'test without message' => [
        'context' => 'context_example',
        'message' => null
      ],
      'test without context and message' => [
        'context' => null,
        'message' => null
      ],
    ];
  }

  public function testHasErrors()
  {
    $notification = new Notification();
    $hasErrors = $notification->hasErrors();
    $this->assertFalse($hasErrors);

    $notification->addError([
      'context' => 'context_example',
      'message' => 'context_example title is required',
    ]);
    $this->assertTrue($notification->hasErrors());
  }

  public function testMessage()
  {
    $notification = new Notification();
    $notification->addError([
      'context' => 'context_example',
      'message' => 'title is required',
    ]);
    $notification->addError([
      'context' => 'context_example',
      'message' => 'description is required',
    ]);
    $message = $notification->messages();

    $this->assertIsString($message);
    $this->assertEquals(
      expected: 'context_example: title is required,context_example: description is required',
      actual: $message
    );
  }

  public function testMessageFilterContext()
  {
    $notification = new Notification();
    $notification->addError([
      'context' => 'context_example',
      'message' => 'title is required',
    ]);
    $notification->addError([
      'context' => 'other_context_example',
      'message' => 'name is required',
    ]);

    $this->assertCount(2, $notification->getErrors());

    $message = $notification->messages(
      context: 'other_context_example'
    );
    $this->assertIsString($message);
    $this->assertEquals(
      expected: 'other_context_example: name is required',
      actual: $message
    );
  }
}
