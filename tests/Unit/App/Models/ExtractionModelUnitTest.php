<?php

namespace Tests\Unit\App\Models;

use App\Models\ExtractionModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExtractionModelUnitTest extends ModelTestCase
{
  public function model(): Model
  {
    return new ExtractionModel();
  }

  protected function traits(): array
  {
    return [
      SoftDeletes::class,
    ];
  }

  protected function fillables(): array
  {
    return [
      'id',
      'ecommerce_id',
      'extraction_type_id',
      'subcategory_id',
      'is_active',
      'settings',
      'created_at',
    ];
  }

  protected function casts(): array
  {
    return [
      'id' => 'string',
      'deleted_at' => 'datetime',
    ];
  }

}
