<?php

namespace App\Services\Queue;

use App\Services\Clients\AWSClient;
use Aws\Sqs\SqsClient;
use Core\Domain\Services\Queue\QueueInterface;


class AwsSqsService implements QueueInterface
{

  protected readonly SqsClient $sqsClient;

  public function __construct(AWSClient $awsClient)
  {
      $this->sqsClient = $awsClient->getSqsClient();
  }

  /**
   * Envia mensagens em lote para a fila SQS.
   *
   * @param string $queueUrl
   * @param array $messages Um array de mensagens, cada uma com um 'Id' e um 'Body'.
   * @return array O array de IDs das mensagens enviadas.
   */
  public function sendMessagesBatch(string $queueUrl, array $messages): array
  {
    if(app()->environment() == 'testing'){
      return [];
    }
    $messageIds = [];

    // Divide as mensagens em lotes de 10
    $chunks = array_chunk($messages, 10);

    // Envia cada lote de 10 mensagens
    foreach ($chunks as $chunk) {
      // Formata as mensagens para o formato esperado pelo SQS
      $formattedMessages = array_map(fn($message, $index) => [
        'Id' => (string) $index,  // Cada mensagem deve ter um ID único dentro do lote
        'MessageBody' => json_encode($message),
      ], $chunk, array_keys($chunk));

      // Envia o lote de mensagens
      $result = $this->sqsClient->sendMessageBatch([
        'QueueUrl' => $queueUrl,
        'Entries' => $formattedMessages,
      ]);
      
      // Adiciona os IDs das mensagens enviadas ao array final
      $successfulMessages = $result->get('Successful');
      $messageIds = array_merge($messageIds, array_map(fn($message) => $message['MessageId'], $successfulMessages));
    }

    // Retorna os IDs das mensagens enviadas
    return $messageIds;
  }

}