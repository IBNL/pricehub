<?php

namespace Tests\Unit\App\Models;

use App\Models\BrandModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class BrandModelUnitTest extends ModelTestCase
{
  public function model(): Model
  {
    return new BrandModel();
  }

  protected function traits(): array
    {
        return [
            HasFactory::class,
            SoftDeletes::class,
        ];
    }

    protected function fillables(): array
    {
        return [
            'id',
            'name',
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
