<?php

namespace Tests\Unit\App\Models;

use App\Models\ProductModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductModelUnitTest extends ModelTestCase
{
  public function model(): Model
  {
    return new ProductModel();
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
          'brand_id',
          'subcategory_id',
          'is_active',
          'url',
          'name',
          'slug',
          'last_date_price',
          'last_price',
          'logo',
          'logo_from_ecommerce',
          'available',
          'created_at',
        ];
    }

    protected function casts(): array
    {
        return [
            'id' => 'string',
            'last_date_price' => 'datetime:d/m/Y',
            'deleted_at' => 'datetime',
        ];
    }
}
