<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ProductModel extends Model
{
  use SoftDeletes;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = "products";

  /**
   * Indicates if the model's ID is auto-incrementing.
   *
   * @var bool
   */
  public $incrementing = false;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'id',
    'ecommerce_id',
    'brand_id',
    'subcategory_id',
    'is_active',
    'url',
    'name',
    'slug',
    'last_date_price',
    'last_price',
    'logo',
    'logo_from_ecommerce',
    'available',
    'created_at'
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array
   */
  protected $hidden = [
    'created_at',
    'updated_at',
    'deleted_at'
  ];

  protected function casts(): array
  {
    return [
      'id' => 'string',
      'last_date_price' => 'datetime:d/m/Y',
      'deleted_at' => 'datetime',
    ];
  }

  /*public function brand(): HasOne
  {
    return $this->hasOne(BrandModel::class, 'id', 'brand_id');
  }

  public function subcategory(): HasOne
  {
    return $this->hasOne(SubcategoryModel::class, 'id', 'subcategory_id');
  }*/

  public function scopeFilter(Builder $query, Collection $data)
  {
    $query->url($data)
      ->subcategoryId($data)
      ->available($data);
  }


  public function scopeUrl(Builder $query, Collection $data): void
  {
    if ($data->get('url')) {
      $query->where('products.url', $data['url']);
    }
  }

  public function scopeSubcategoryId(Builder $query, Collection $data): void
  {
    if ($data->get('subcategory_id')) {
      $query->where('products.subcategory_id', $data['subcategory_id']);
    }
  }

  public function scopeAvailable(Builder $query, Collection $data): void
  {
    if ($data->get('available') || $data->get('available') == '0') {
      $query->where('products.available', boolval($data['available']));
    }
  }
}
