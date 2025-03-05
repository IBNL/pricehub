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

  public function index(array $filterData = []): array
  {
    $query = BrandModel::select(
      'id',
      'name',
      'logo'
    );
    if ($filterData) {
      $query->filter(collect($filterData));
    }

    return $query->get()->toArray();
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

  public function getBrandNeedCreate(array $brands): array
  {
    $brandsUnique = collect($brands)->unique('name')->values()->all();

    $brandsInDatabase = $this->model::all()->pluck('name')->toArray();

    $brandNeedCreate = array_filter($brandsUnique, function ($brand) use ($brandsInDatabase) {
      return !in_array($brand['name'], $brandsInDatabase);
    });

    return $brandNeedCreate;
  }

}