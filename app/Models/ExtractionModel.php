<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExtractionModel extends Model
{
  use SoftDeletes;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = "extractions";

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
    'extraction_type_id',
    'subcategory_id',
    'is_active',
    'settings',
    'created_at',
  ];

  protected $casts = [
    'id' => 'string',
    'deleted_at' => 'datetime',
  ];
}
