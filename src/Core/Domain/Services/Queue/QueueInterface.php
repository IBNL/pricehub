<?php

namespace Core\Domain\Services\Queue;

interface QueueInterface
{

  public function sendMessagesBatch(string $queueUrl, array $messages): array;

}