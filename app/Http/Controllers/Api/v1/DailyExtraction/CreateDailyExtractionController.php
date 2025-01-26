<?php

namespace App\Http\Controllers\Api\v1\DailyExtraction;

use App\Adapter\ApiAdapter;
use App\Http\Controllers\Controller;
use Core\UseCase\DailyExtraction\CreateDailyExtractionUseCase;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class CreateDailyExtractionController extends Controller
{

  public function __invoke(CreateDailyExtractionUseCase $createDailyExtractionUseCase): JsonResponse
  {
    $response = $createDailyExtractionUseCase->execute();

    $statusCode = empty($response->data) ? Response::HTTP_OK : Response::HTTP_CREATED;

    return ApiAdapter::toJson(
      data: $response,
      statusCode: $statusCode
    );
  }
}