<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class SubcategoryModel extends Model
{
  use SoftDeletes;

  public $table = 'subcategories';

  public $incrementing = false;


  protected $fillable = [
    'id',
    'category_id',
    'name',
    'slug',
    'logo',
    'created_at',
  ];

  protected $casts = [
    'id' => 'string',
    'deleted_at' => 'datetime',
  ];

}
