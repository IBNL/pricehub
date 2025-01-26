<?php

namespace Tests\Unit\App\Models;

use App\Models\DailyExtractionModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyExtractionModelUnitTest extends ModelTestCase
{
  public function model(): Model
  {
    return new DailyExtractionModel();
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
      'extraction_id',
      'extraction_success',
      'reference_date',
      'send_to_process',
      'input',
      'output',
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
