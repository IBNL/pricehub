<?php

namespace Tests\Stubs;

use Core\Domain\Services\Queue\QueueInterface;

class AwsSqsServiceStub implements QueueInterface
{
  public function sendMessagesBatch(string $queueUrl = null, array $messages = null): array
  {
    return [];
  }
}


