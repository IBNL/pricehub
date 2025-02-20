<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class PriceHistoryExtractionModel extends Model
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = "price_history_extractions";

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
    'extraction_id',
    'product_id',
    'reference_date',
    'price',
    'created_at'
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array
   */
  /*protected $hidden = [
    'created_at',
    'updated_at',
  ];*/

  protected function casts(): array
  {
    return [
      'id' => 'string',
      'reference_date' => 'datetime:d/m/Y',
    ];
  }

  public function scopeFilter(Builder $query, Collection $data)
  {
    $query->referenceDate($data)
      ->product($data);
  }


  public function scopeProduct(Builder $query, Collection $data): void
  {
    if ($data->get('product_id')) {
      $query->whereIn('price_history_extractions.product_id', $data['product_id']);
    }

  }

  public function scopeReferenceDate(Builder $query, Collection $data): void
  {
    if ($data->get('start_reference_date') && $data->get('end_reference_date')) {
      $query->whereBetween('price_history_extractions.reference_date', [$data['start_reference_date'], $data['end_reference_date']]);
    }
  }

}
