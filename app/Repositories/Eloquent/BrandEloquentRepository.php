<?php

namespace App\Repositories\Eloquent;

use App\Models\BrandModel;
use Core\Domain\Repository\BrandRepositoryInterface;
use Illuminate\Support\Facades\DB;


class BrandEloquentRepository implements BrandRepositoryInterface
{
  public function __construct(
    protected BrandModel $model
  ) {
  }

  public function insertBatch(array $data): array
  {
    $brands = [];
    foreach ($data as $item) {
      $brands[] = [
        'id' => $item->id(),
        'name' => $item->name,
        'logo' => $item->logo === '' ? null : $item->logo,
        'created_at' => $item->createdAt(),
      ];
    }

    DB::transaction(function () use ($brands) {
      DB::table($this->model->getTable())->insert($brands);
    });

    return $brands;
  }

}