<?php

namespace Core\UseCase\Product;

use Core\Domain\Entity\BrandEntity;
use Core\Domain\Entity\PriceHistoryExtractionEntity;
use Core\Domain\Entity\ProductEntity;
use Core\Domain\Repository\BrandRepositoryInterface;
use Core\Domain\Repository\DailyExtractionInterface;
use Core\Domain\Repository\PriceHistoryExtractionRepositoryInterface;
use Core\Domain\Repository\ProductRepositoryInterface;
use Core\Domain\ValueObject\ValueObjectUuid;
use Core\UseCase\Product\DTO\ProductProcessingInputDto;
use DateTime;

class ProductProcessingUseCase
{
  protected array $productsFromQueue;
  protected array $preparedProducts;
  protected string $extraction_id;
  protected string $ecommerce_id;
  protected string $reference_date;
  protected bool $extraction_success;
  protected string $subcategory_id;
  protected $data;

  public function __construct(
    private BrandRepositoryInterface $brandRepository,
    private ProductRepositoryInterface $productRepository,
    private PriceHistoryExtractionRepositoryInterface $priceHistoryExtractionRepository,
    private DailyExtractionInterface $dailyExtractionRepository//add repository no nome
  ) {
  }

  public function execute(ProductProcessingInputDto $input)
  {

    $this->data = json_decode($input->data);
    $this->initializeData();
    $this->createBrands();
    $this->prepareProductCollection();
    $this->createProducts();
    $this->createPriceHistoryExtraction();
    $this->updateDailyExtractions();
    $this->updateProduct();

  }

  private function initializeData(): void
  {
    $this->productsFromQueue = $this->data->products;
    $this->extraction_id = $this->data->extraction_id;
    $this->ecommerce_id = $this->data->ecommerce_id;
    $this->subcategory_id = $this->data->subcategory_id;
    $this->reference_date = $this->data->reference_date;
    $this->extractionSuccess = false;
  }

  private function createBrands(): void
  {
    $brands = [];
    foreach ($this->productsFromQueue as $product) {
      $brands[] = ['name' => $product->brand];
    }

    $brandsNeedCreate = $this->brandRepository->getBrandNeedCreate($brands);
    if (!empty($brandsNeedCreate)) {
      $brandEntities = [];
      foreach ($brandsNeedCreate as $brand) {
        $brandEntity = new BrandEntity(
          name: $brand['name'],
        );
        array_push($brandEntities, $brandEntity);
      }
      $this->brandRepository->insertBatch($brandEntities);
    }
  }

  private function prepareProductCollection(): void
  {
    $allBrandFromDatabase = $this->brandRepository->index();
    $productFilter = [];
    $productFilter['ecommerce_id'] = $this->ecommerce_id;

    $productsFilterFromDatabase = $this->productRepository->index($productFilter);

    $this->preparedProducts = array_map(function ($item) use ($allBrandFromDatabase, $productsFilterFromDatabase) {

      $product = null;
      foreach ($productsFilterFromDatabase as $productFromDatabase) {
        if ($productFromDatabase['url'] === $item->url) {
          $product = $productFromDatabase;
          break;
        }
      }
      $item->product_id = $product ? $product['id'] : null;

      if (isset($item->brand)) {
        $brand = null;
        foreach ($allBrandFromDatabase as $brandFromDatabase) {
          if ($brandFromDatabase['name'] === $item->brand) {
            $brand = $brandFromDatabase;
            break;
          }
        }
        $item->brand_id = $brand ? $brand['id'] : null;
      }

      $item->extraction_id = $this->extraction_id;
      $item->ecommerce_id = $this->ecommerce_id;
      $item->subcategory_id = $this->subcategory_id;
      $item->is_active = true;
      $item->available = $item->availability;
      $item->logo_from_ecommerce = $item->image;
      $item->reference_date = $this->reference_date;
      return $item;
    }, $this->productsFromQueue);
  }

  private function createProducts(): void
  {
    $productsToInsert = array_filter($this->preparedProducts, function ($item) {
      return is_null($item->product_id);
    });

    if (!empty($productsToInsert)) {
      $productEntities = [];
      foreach ($productsToInsert as $product) {
        $productEntity = new ProductEntity(
          is_active: $product->is_active,
          url: $product->url,
          name: $product->name,
          #slug: $product->slug,
          available: $product->available,
          ecommerce_id: new ValueObjectUuid($product->ecommerce_id) ?? null,
          brand_id: new ValueObjectUuid($product->brand_id) ?? null,
          subcategory_id: new ValueObjectUuid($product->subcategory_id) ?? null,
          last_date_price: $product->last_date_price ?? null,
          last_price: $product->last_price ?? null,
          logo_from_ecommerce: $product->logo_from_ecommerce ?? null,
          logo: $product->logo ?? null,
        );
        array_push($productEntities, $productEntity);
      }
      $this->productRepository->insertBatch(data: $productEntities);
      $this->setProductIdAfterCreate();
    }
  }

  private function setProductIdAfterCreate()
  {
    $productFilter = ['ecommerce_id' => $this->ecommerce_id];

    $productsFilterFromDatabase = $this->productRepository->index($productFilter);

    $this->preparedProducts = array_map(function ($item) use ($productsFilterFromDatabase) {

      $product = null;
      foreach ($productsFilterFromDatabase as $productFromDatabase) {
        if ($productFromDatabase['url'] === $item->url) {
          $product = $productFromDatabase;
          break;
        }
      }
      $item->product_id = $product ? $product['id'] : null;
      return $item;
    }, $this->preparedProducts);

  }

  private function createPriceHistoryExtraction(): void
  {
    $priceHistoryExtractionToInsert = array_filter($this->preparedProducts, function ($product) {
      return !empty($product->price);
    });

    if (!empty($priceHistoryExtractionToInsert)) {
      $priceHistoryExtractionEntities = [];
      foreach ($priceHistoryExtractionToInsert as $priceHistoryExtraction) {
        $priceHistoryExtractionEntity = new PriceHistoryExtractionEntity(
          extraction_id: new ValueObjectUuid($priceHistoryExtraction->extraction_id),
          product_id: new ValueObjectUuid($priceHistoryExtraction->product_id),
          reference_date: new DateTime($priceHistoryExtraction->reference_date),
          price: $priceHistoryExtraction->price,
        );
        array_push($priceHistoryExtractionEntities, $priceHistoryExtractionEntity);
      }
      $this->priceHistoryExtractionRepository->insertBatch(data: $priceHistoryExtractionEntities);
    }
  }

  private function updateDailyExtractions(): void
  {

    $dailyExtractionToSearch = [];
    $dailyExtractionToSearch['extraction_id'] = $this->extraction_id;
    $dailyExtractionToSearch['reference_date'] = $this->reference_date;
    $dailyExtractionFromDatabase = $this->dailyExtractionRepository->findByColumns(columns: $dailyExtractionToSearch);

    $dailyExtractionFromDatabase->update(
      extraction_success: true,
      output: json_encode($this->data)
    );

    $this->dailyExtractionRepository->update($dailyExtractionFromDatabase);
  }

  private function updateProduct(): void
  {
    $productWithPrice = array_filter($this->preparedProducts, function ($item) {
      return !is_null($item->price);
    });
    $productsEntitiesToUpdate = [];
    foreach ($productWithPrice as $item) {
      $productEntity = new ProductEntity(
        id: new ValueObjectUuid($item->product_id),
        is_active: true,
        url: $item->url,
        name: $item->name,
        available: true,
        ecommerce_id: new ValueObjectUuid($item->ecommerce_id),
        brand_id: new ValueObjectUuid($item->brand_id),
        subcategory_id: new ValueObjectUuid($item->subcategory_id),
        last_date_price: new DateTime($this->reference_date),
        last_price: $item->price,
        logo_from_ecommerce: $item->logo_from_ecommerce,
      );

      array_push($productsEntitiesToUpdate, $productEntity);
    }
    $this->productRepository->updateBatch(data:$productsEntitiesToUpdate);
  }


}