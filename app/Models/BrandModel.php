<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BrandModel extends Model
{
  use HasFactory;
  use SoftDeletes;

  public $table = 'brands';

  public $incrementing = false;


  protected $fillable = [
    'id',
    'name',
    'logo',
    'created_at',
  ];

  protected $casts = [
    'id' => 'string',
    'deleted_at' => 'datetime',
  ];

}
