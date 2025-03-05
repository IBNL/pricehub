<?php

namespace Tests\Unit\App\Models;

use App\Models\SubcategoryModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class SubcategoryModelUnitTest extends ModelTestCase
{
  public function model(): Model
  {
    return new SubcategoryModel();
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
          'category_id',
          'name',
          'slug',
          'logo',
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
