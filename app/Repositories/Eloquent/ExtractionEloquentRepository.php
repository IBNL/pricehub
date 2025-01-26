<?php

namespace App\Repositories\Eloquent;

use App\Models\ExtractionModel;
use Core\Domain\Repository\ExtractionRepositoryInterface;
use DateTime;
use Illuminate\Database\Query\JoinClause;


class ExtractionEloquentRepository implements ExtractionRepositoryInterface
{
  public function __construct(
    protected ExtractionModel $model
  ) {
  }

  public function createDailyExtraction(): array
  {
    $referenceDate = new DateTime()->format('Y-m-d');

    $query = ExtractionModel::selectRaw('extractions.ecommerce_id as ecommerce_id')
      ->selectRaw('extractions.id as extraction_id')
      ->selectRaw('extractions.subcategory_id as subcategory_id')
      ->selectRaw('extractions.extraction_type_id')
      ->selectRaw('extraction_types.name as extraction_type_name')
      ->selectRaw('? as reference_date', [$referenceDate])
      ->selectRaw('True as send_to_process')
      ->selectRaw('False as extraction_success')
      #->selectRaw("CONCAT('src/Storage/',extractions.id, '/', YEAR(CURDATE()), '/', MONTH(CURDATE()), '/', DAY(CURDATE()), '/') AS file_path")
      ->selectRaw('extractions.settings')
      ->leftJoin('daily_extractions', function (JoinClause $leftJoin) use ($referenceDate) {
        $leftJoin->on('extractions.id', '=', 'daily_extractions.extraction_id')
          ->where('daily_extractions.reference_date', '=', $referenceDate);
      })
      ->join('extraction_types', 'extractions.extraction_type_id', 'extraction_types.id')
      ->where('extractions.is_active', true)
      ->whereNull('daily_extractions.extraction_id')
      #->limit(10)
      ->get();

      // foi necessario passar para o php para nao quebrar quando for criar o test de integracao
    // no sqlite nao existe as funcoes de data YEAR(CURDATE()), MONTH(CURDATE()) e etc
    $data = $query->map(function ($item) {
      $item['file_path'] = 'src/Storage/' . $item['extraction_id'] . '/' .
        now()->year . '/' .
        now()->month . '/' .
        now()->day . '/';
      return $item;
    });

    return $data->toArray();
  }
}