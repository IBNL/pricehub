<?php

namespace Core\UseCase\DailyExtraction;

use Core\Domain\Entity\DailyExtractionEntity;
use Core\Domain\Repository\DailyExtractionRepositoryInterface;
use Core\Domain\Repository\ExtractionRepositoryInterface;
use Core\Domain\Services\Queue\QueueInterface;
use Core\Domain\ValueObject\ValueObjectUuid;
use Core\UseCase\DailyExtraction\DTO\CreateBatchOutputDailyExtractionDTO;
use DateTime;

class CreateDailyExtractionUseCase
{
  public function __construct(
    protected ExtractionRepositoryInterface $extractionRepository,
    protected DailyExtractionRepositoryInterface $dailyExtractionRepository,
    protected QueueInterface $queueService

  ) {
  }

  public function execute(): CreateBatchOutputDailyExtractionDTO
  {
    // montar dailyExtraction
    $dailyExtractionData = $this->extractionRepository->createDailyExtraction();
    if (empty($dailyExtractionData)) {
      return new CreateBatchOutputDailyExtractionDTO(data: []);
    }

    // criar entidade
    $dailyExtractionEntities = [];
    foreach ($dailyExtractionData as $dailyExtraction) {
      $dailyExtractionEntity = new DailyExtractionEntity(
        extraction_id: new ValueObjectUuid($dailyExtraction['extraction_id']),
        reference_date: new DateTime($dailyExtraction['reference_date']),
        extraction_success: $dailyExtraction['extraction_success'],
        send_to_process: $dailyExtraction['send_to_process'],
        input: json_encode($dailyExtraction),
      );
      array_push($dailyExtractionEntities, $dailyExtractionEntity);
    }

    // inserir no banco
    $response = $this->dailyExtractionRepository->insertBatch(data: $dailyExtractionEntities);

    // enviar para fila
    $this->queueService->sendMessagesBatch(
      //queueUrl: config('queue.connections.sqs.subcategory_queue'),
      queueUrl:'https://sqs.sa-east-1.amazonaws.com/009160032176/production_subcategory_extractions',
      messages: $dailyExtractionData
    );

    // retornar
    return new CreateBatchOutputDailyExtractionDTO(data: $response);
  }

}