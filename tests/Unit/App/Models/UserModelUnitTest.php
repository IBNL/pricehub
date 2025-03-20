<?php

namespace Tests\Unit\App\Models;

use App\Models\User as UserModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use PHPUnit\Framework\TestCase;
use Laravel\Sanctum\HasApiTokens;

class UserModelUnitTest extends ModelTestCase
{

  public function model(): Model
  {
    return new UserModel();
  }

  protected function traits(): array
  {
    return [
      HasFactory::class,
      Notifiable::class,
      HasApiTokens::class
    ];
  }

  protected function fillables(): array
  {
    return [
      'uuid',
      'name',
      'email',
      'password',
      'created_at',
    ];
  }

  protected function casts(): array
  {
    return [
      'id' => 'int',
      'uuid' => 'string',
      'email_verified_at' => 'datetime',
      'password' => 'hashed',
    ];
  }

  public function testIncrementing()
  {
    $model = $this->model();

    $this->assertTrue($model->incrementing);
  }

}
