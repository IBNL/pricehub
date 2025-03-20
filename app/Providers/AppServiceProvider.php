<?php

namespace App\Providers;

use App\Repositories\Eloquent\BrandEloquentRepository;
use App\Repositories\Eloquent\DailyExtractionEloquentRepository;
use App\Repositories\Eloquent\ExtractionEloquentRepository;
use App\Repositories\Eloquent\UserEloquentRepository;
use App\Services\Authenticate\SanctumService;
use App\Services\Queue\AwsSqsService;
use Core\Domain\Repository\BrandRepositoryInterface;
use Core\Domain\Repository\DailyExtractionRepositoryInterface;
use Core\Domain\Repository\ExtractionRepositoryInterface;
use Core\Domain\Repository\UserRepositoryInterface;
use Core\Domain\Services\Authenticate\AuthenticateInterface;
use Core\Domain\Services\Queue\QueueInterface;
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
      DailyExtractionRepositoryInterface::class,
      DailyExtractionEloquentRepository::class
    );

    $this->app->singleton(
      UserRepositoryInterface::class,
      UserEloquentRepository::class
    );


    /**
     * Services
     */
    $this->app->bind(
      QueueInterface::class,
      AwsSqsService::class,
    );

    $this->app->bind(
      AuthenticateInterface::class,
      SanctumService::class,
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
