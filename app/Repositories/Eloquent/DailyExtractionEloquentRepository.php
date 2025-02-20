<?php

namespace App\Repositories\Eloquent;

use App\Models\DailyExtractionModel;
use Core\Domain\Entity\DailyExtractionEntity;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\DailyExtractionInterface;
use Core\Domain\ValueObject\ValueObjectUuid;
use DateTime;
use DB;


class DailyExtractionEloquentRepository implements DailyExtractionInterface
{

  public function __construct(
    protected DailyExtractionModel $model
  ) {
  }

  public function insertBatch(array $data): array
  {
    $dailyExtraction = [];
    foreach ($data as $item) {
      $dailyExtraction[] = [
        'id' => $item->id(),
        'extraction_id' => $item->extraction_id,
        'input' => $item->input,
        'output' => $item->output,
        //'output' => $item->output === '' ? null : $item->output,
        'extraction_success' => $item->extraction_success,
        'reference_date' => $item->reference_date,
        'send_to_process' => $item->send_to_process,
        'created_at' => $item->createdAt(),
      ];
    }

    DB::transaction(function () use ($dailyExtraction) {
      DB::table($this->model->getTable())->insert($dailyExtraction);
    });

    return $dailyExtraction;
  }

  public function findByColumns(array $columns): ?DailyExtractionEntity
  {

    $model = $this->model->where($columns)->first();

    return $model ? $this->convertToEntity($model) : null;
  }

  public function update(DailyExtractionEntity $dailyExtractionEntity): DailyExtractionEntity
    {
        if (! $dailyExtractionEntityDb = $this->model->find($dailyExtractionEntity->id)) {
            throw new NotFoundException("daily_extractions {$dailyExtractionEntity->id} not found");
        }

        $dailyExtractionEntityDb->update([
            'extraction_success' => $dailyExtractionEntity->extraction_success,
            'output' => $dailyExtractionEntity->output,
        ]);

        $model = $dailyExtractionEntityDb->refresh();

        return $this->convertToEntity(model:$model);
    }

  private function convertToEntity(DailyExtractionModel $model): DailyExtractionEntity
  {
    $entity = new DailyExtractionEntity(
      id: new ValueObjectUuid($model->id),
      extraction_id: new ValueObjectUuid($model->extraction_id),
      input: $model->input,
      output: $model->output,
      extraction_success: $model->extraction_success,
      reference_date: new DateTime($model->reference_date),
      send_to_process: $model->send_to_process,
      created_at: new DateTime($model->created_at)
    );

    return $entity;
  }
  

}