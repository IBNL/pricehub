<?php

namespace Core\UseCase\DailyExtraction;

use Core\Domain\Entity\DailyExtractionEntity;
use Core\Domain\Repository\DailyExtractionRepositoryInterface;
use Core\Domain\Repository\ExtractionRepositoryInterface;
use Core\Domain\ValueObject\ValueObjectUuid;
use Core\UseCase\DailyExtraction\DTO\CreateBatchOutputDailyExtractionDTO;
use DateTime;

class CreateDailyExtractionUseCase
{
  public function __construct(
    protected ExtractionRepositoryInterface $extractionRepository,
    protected DailyExtractionRepositoryInterface $dailyExtractionRepository

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

    // retornar
    return new CreateBatchOutputDailyExtractionDTO(data: $response);
  }

}