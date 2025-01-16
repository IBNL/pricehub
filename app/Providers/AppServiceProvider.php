<?php

namespace App\Providers;

use App\Repositories\Eloquent\BrandEloquentRepository;
use Core\Domain\Repository\BrandRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    /**
     * Repositories
     */
    $this->app->singleton(
      BrandRepositoryInterface::class,
      BrandEloquentRepository::class
    );
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    //
  }
}
