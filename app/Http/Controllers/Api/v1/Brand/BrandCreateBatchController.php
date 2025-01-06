<?php

namespace App\Http\Controllers\Api\v1\Brand;

use App\Adapter\ApiAdapter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Brand\CreateBatchBrandRequest;
use Core\UseCase\Brand\CreateBatchBrandUseCase;
use Core\UseCase\Brand\DTO\CreateBatchInputBrandDTO;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class BrandCreateBatchController extends Controller
{
  public function __invoke(CreateBatchBrandRequest $request, CreateBatchBrandUseCase $createBatchBrandUseCase): JsonResponse
  {
    $data = [];
    foreach ($request->all() as $item) {
      $data[] = [
        'name' => $item['name'],
        'logo' => $item['logo'] ?? '',
      ];
    }

    $response = $createBatchBrandUseCase->execute(
      input: new CreateBatchInputBrandDTO(
        data: $data
      )
    );

    return ApiAdapter::toJson(
      data: $response,
      statusCode: Response::HTTP_CREATED
    );

  }
}
