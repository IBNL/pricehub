<?php

namespace App\Jobs;

use Core\UseCase\Product\DTO\ProductProcessingInputDto;
use Core\UseCase\Product\ProductProcessingUseCase;
use Illuminate\Queue\Jobs\SqsJob;


class ProductProcessing
{

  public function __construct(
    protected ProductProcessingUseCase $productProcessingUseCase
  ) {
  }

  public function call(SqsJob $job, array $data): void
  {
    $this->productProcessingUseCase->execute(
      input: new ProductProcessingInputDto(
        data: json_encode($data)
      )
    );

    $job->delete();
  }

}