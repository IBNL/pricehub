<?php

namespace Core\UseCase\Product;

use Core\Domain\Entity\ProductEntity;
use Core\Domain\Repository\ProductRepositoryInterface;
use Core\UseCase\Product\DTO\CreateBatchInputProductDTO;
use Core\UseCase\Product\DTO\CreateBatchOutputProductDTO;

class CreateBatchProductUseCase
{
  public function __construct(
    protected ProductRepositoryInterface $productRepository
  ) {
  }

  public function execute(CreateBatchInputProductDTO $input): CreateBatchOutputProductDTO
  {
    $productEntities = [];
    foreach ($input->data as $product) {
      $productEntity = new ProductEntity(
        is_active: $product['is_active'],
        url: $product['url'],
        name: $product['name'],
        slug: $product['slug'],
        available: $product['available'],
        ecommerce_id: $product['ecommerce_id'] ?? null,
        brand_id: $product['brand_id'] ?? null,
        subcategory_id: $product['subcategory_id'] ?? null,
        last_date_price: $product['last_date_price'] ?? null,
        last_price: $product['last_price'] ?? null,
        logo_from_ecommerce: $product['logo_from_ecommerce'] ?? null,
        logo: $product['logo'] ?? null,
      );
      array_push($productEntities, $productEntity);
    }

    $response = $this->productRepository->insertBatch(data: $productEntities);

    return new CreateBatchOutputProductDTO(data: $response);
  }
}