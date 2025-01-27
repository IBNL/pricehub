<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

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

  public function scopeFilter(Builder $query, Collection $data)
    {
        $query->name($data);
    }

    public function scopeName(Builder $query, Collection $data): void
    {
        if ($data->get('name')) {
            $query->where('brands.name', $data['name']);
        }
    }
}
