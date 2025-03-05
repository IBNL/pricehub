<?php

namespace App\Repositories\Eloquent;

use App\Models\ProductModel;
use Core\Domain\Repository\ProductRepositoryInterface;
use DB;
use Illuminate\Support\Str;

class ProductEloquentRepository implements ProductRepositoryInterface
{
  public function __construct(
    protected ProductModel $model
  ) {
  }

  public function index(array $filterData = null): array|null
  {

    /*$query = ProductModel::with('brand')
      ->with('subcategory');*/
    //->join('subcategories', 'subcategories.id', 'products.subcategory_id');
    $query = ProductModel::select(
      'products.id',
      'products.ecommerce_id',
      'products.brand_id',
      'products.subcategory_id',
      'products.is_active',
      'products.url',
      'products.name',
      'products.slug',
      'products.last_date_price',
      'products.last_price',
      'products.logo',
      'products.logo_from_ecommerce',
      'products.available'
    );
    if ($filterData) {
      $query->filter(collect($filterData));
    }

    return $query->get()->toArray();
  }

  public function insertBatch(array $data): array
  {
    $products = [];
    foreach ($data as $item) {
      $products[] = [
        'id' => $item->id(),
        'is_active' => $item->is_active,
        'url' => $item->url,
        'name' => $item->name,
        'slug' => Str::slug($item->name),
        'available' => $item->available,
        'ecommerce_id' => $item->ecommerce_id ?? null,
        'brand_id' => $item->brand_id ?? null,
        'subcategory_id' => $item->subcategory_id ?? null,
        'last_date_price' => $item->last_date_price ?? null,
        'last_price' => $item->last_price ?? null,
        'logo_from_ecommerce' => $item->logo_from_ecommerce ?? null,
        'logo' => $item->logo ?? null,
        'created_at' => $item->createdAt(),
      ];
    }

    DB::transaction(function () use ($products) {
      DB::table($this->model->getTable())->insert($products);
    });

    return $products;
  }

  public function updateBatch(array $data): void
  {

    $products = [];
    foreach ($data as $item) {
      $products[] = [
        'id' => $item->id(),
        'available' => $item->available,
        'is_active' => $item->is_active,
        'last_date_price' => $item->last_date_price,
        'last_price' => $item->last_price,
      ];
    }

    foreach ($products as $item) {
      DB::table('products')->where('id', $item['id'])->update($item);
    }
  }
}