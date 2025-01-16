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
  /**
   * @OA\Post(
   *     path="api/v1/brand/create-batch",
   *     tags={"Brand"},
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(
   *             type="array",
   *             @OA\Items(
   *                 type="object",
   *                 @OA\Property(property="name", type="string", example="brand1"),
   *                 @OA\Property(property="logo", type="string", example="logo1")
   *             ),
   *             example={
   *                 {"name": "brand1", "logo": "logo1"},
   *                 {"name": "brandName2", "logo": "logo2"},
   *                 {"name": "brand3", "logo": "logo3"}
   *             }
   *         )
   *     ),
   *     @OA\Response(
   *         response=201,
   *         description="successful operation",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="data", type="array",
   *                 @OA\Items(
   *                     type="object",
   *                     @OA\Property(property="id", type="string", example="ff65b0f1-089b-4b11-a980-33313a77bd59"),
   *                     @OA\Property(property="name", type="string", example="brand1"),
   *                     @OA\Property(property="logo", type="string", example="logo1"),
   *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-07 21:38:57")
   *                 )
   *             ),
   *             example={
   *                 "data": {
   *                     {
   *                         "id": "ff65b0f1-089b-4b11-a980-33313a77bd59",
   *                         "name": "brand1",
   *                         "logo": "logo1",
   *                         "created_at": "2025-01-07 21:38:57"
   *                     },
   *                     {
   *                         "id": "f8688da3-c7ea-491b-a4b8-b2d735b4d678",
   *                         "name": "brandName2",
   *                         "logo": "logo2",
   *                         "created_at": "2025-01-07 21:38:57"
   *                     },
   *                     {
   *                         "id": "381cba3d-2c05-43ee-94d1-4c76779dd64c",
   *                         "name": "brand3",
   *                         "logo": "logo3",
   *                         "created_at": "2025-01-07 21:38:57"
   *                     }
   *                 }
   *             }
   *         )
   *     ),
   *     @OA\Response(
   *         response=401,
   *         description="Unauthorized",
   *         @OA\JsonContent(ref="#/components/schemas/Response401")
   *     ),
   *     @OA\Response(
   *         response=422,
   *         description="Unprocessable entity",
   *         @OA\JsonContent(ref="#/components/schemas/Response422")
   *     )
   * )
   */
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
