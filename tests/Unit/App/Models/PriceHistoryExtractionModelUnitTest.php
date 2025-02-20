<?php

namespace Tests\Unit\App\Models;

use App\Models\PriceHistoryExtractionModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class PriceHistoryExtractionModelUnitTest extends ModelTestCase
{
  public function model(): Model
  {
    return new PriceHistoryExtractionModel();
  }

  protected function traits(): array
  {
    return [];
  }

  protected function fillables(): array
  {
    return [
      'id',
      'extraction_id',
      'product_id',
      'reference_date',
      'price',
      'created_at',
    ];
  }

  protected function casts(): array
  {
    return [
      'id' => 'string',
      'reference_date' => 'datetime:d/m/Y',
    ];
  }


}
