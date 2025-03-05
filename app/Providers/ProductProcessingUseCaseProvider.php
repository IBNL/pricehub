<?php

namespace App\Providers;

use App\Repositories\Eloquent\BrandEloquentRepository;
use App\Repositories\Eloquent\DailyExtractionEloquentRepository;
use App\Repositories\Eloquent\PriceHistoryExtractionEloquentRepository;
use App\Repositories\Eloquent\ProductEloquentRepository;
use Core\Domain\Repository\BrandRepositoryInterface;
use Core\Domain\Repository\DailyExtractionRepositoryInterface;
use Core\Domain\Repository\PriceHistoryExtractionRepositoryInterface;
use Core\Domain\Repository\ProductRepositoryInterface;
use Core\UseCase\Product\ProductProcessingUseCase;
use Illuminate\Support\ServiceProvider;

class ProductProcessingUseCaseProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    $this->app->bind(BrandRepositoryInterface::class, BrandEloquentRepository::class);
    $this->app->bind(ProductRepositoryInterface::class, ProductEloquentRepository::class);
    $this->app->bind(PriceHistoryExtractionRepositoryInterface::class, PriceHistoryExtractionEloquentRepository::class);
    $this->app->bind(DailyExtractionRepositoryInterface::class, DailyExtractionEloquentRepository::class);

    $this->app->bind(ProductProcessingUseCase::class, function ($app) {
      return new ProductProcessingUseCase(
        brandRepository: $app->make(BrandRepositoryInterface::class),
        productRepository: $app->make(ProductRepositoryInterface::class),
        priceHistoryExtractionRepository: $app->make(PriceHistoryExtractionRepositoryInterface::class),
        dailyExtractionRepository: $app->make(DailyExtractionRepositoryInterface::class)
      );
    });
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
    //
  }
}
