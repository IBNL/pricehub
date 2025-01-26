<?php

namespace App\Providers;

use App\Repositories\Eloquent\BrandEloquentRepository;
use App\Repositories\Eloquent\DailyExtractionEloquentRepository;
use App\Repositories\Eloquent\ExtractionEloquentRepository;
use Core\Domain\Repository\BrandRepositoryInterface;
use Core\Domain\Repository\DailyExtractionInterface;
use Core\Domain\Repository\ExtractionRepositoryInterface;
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

    $this->app->singleton(
      ExtractionRepositoryInterface::class,
      ExtractionEloquentRepository::class
    );

    $this->app->singleton(
      DailyExtractionInterface::class,
      DailyExtractionEloquentRepository::class
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
