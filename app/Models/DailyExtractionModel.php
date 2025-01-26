<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyExtractionModel extends Model
{
  use SoftDeletes;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = "daily_extractions";

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
    'extraction_success',
    'reference_date',
    'send_to_process',
    'input',
    'output',
    'created_at',
  ];

  protected $casts = [
    'id' => 'string',
    'deleted_at' => 'datetime',
  ];
}
