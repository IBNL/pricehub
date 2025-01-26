<?php

namespace App\Repositories\Eloquent;

use App\Models\DailyExtractionModel;
use Core\Domain\Repository\DailyExtractionInterface;
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
        //'output' => $item->output,
        'output' => $item->output === '' ? null : $item->output,
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


}