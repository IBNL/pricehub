<?php

namespace App\Repositories\Eloquent;

use App\Models\PriceHistoryExtractionModel;
use Core\Domain\Repository\PriceHistoryExtractionRepositoryInterface;
use Illuminate\Support\Facades\DB;


class PriceHistoryExtractionEloquentRepository implements PriceHistoryExtractionRepositoryInterface
{

  public function __construct(
    protected PriceHistoryExtractionModel $model
  ) {
  }

  public function index(array $filterData = []): array
  {
    $query = PriceHistoryExtractionModel::select(
      'id',
      'extraction_id',
      'product_id',
      'reference_date',
      'price',
    );
    
    if ($filterData) {
      $query->filter(collect($filterData));
    }

    return $query->get()->toArray();
  }


  public function insertBatch(array $data): array
  {
    $priceHistoryExtractions = [];
    foreach ($data as $item) {
      $priceHistoryExtractions[] = [
        'id' => $item->id(),
        'extraction_id' => $item->extraction_id,
        'product_id' => $item->product_id,
        'reference_date' => $item->reference_date,
        'price' => $item->price,
        'created_at' => $item->createdAt(),
      ];
    }

    DB::transaction(function () use ($priceHistoryExtractions) {
      DB::table($this->model->getTable())->insert($priceHistoryExtractions);
    });

    return $priceHistoryExtractions;
  }

}