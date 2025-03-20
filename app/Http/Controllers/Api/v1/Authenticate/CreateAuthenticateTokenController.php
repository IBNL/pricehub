<?php

namespace App\Http\Controllers\Api\v1\Authenticate;

use App\Adapter\ApiAdapter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Authenticate\CreateAuthenticateTokenRequest;
use Core\UseCase\Authenticate\CreateAuthenticateTokenUseCase;
use Core\UseCase\Authenticate\DTO\CreateAuthenticateTokenInputDTO;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CreateAuthenticateTokenController extends Controller
{
  /**
   * @OA\Post(
   *     path="api/v1/authenticate/create-token",
   *     tags={"Authenticate"},
   *     summary="Gerar token de acesso do usuário",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(
   *             type="array",
   *             @OA\Items(
   *                 type="object",
   *                 @OA\Property(property="email", type="string", example="Bearer"),
   *                 @OA\Property(property="password", type="string", example="password")
   *             ),
   *             example={
   *                 "email": "example@example.com", "password": "password",
   *             }
   *         )
   *     ),
   * @OA\Response(
   *     response=200,
   *     description="successful operation",
   *     @OA\JsonContent(
   *         type="object",
   *         @OA\Property(property="data", 
   *             type="object", 
   *             @OA\Property(property="type", type="string", example="Bearer"),
   *             @OA\Property(property="token", type="string", example="1|yNnFkhW1B5OSwzKdSIwMdxPrH8NZfv86x1RsGArs2dae418c")
   *         ),
   *         example={
   *             "data": {
   *                 "type": "Bearer",
   *                 "token": "1|yNnFkhW1B5OSwzKdSIwMdxPrH8NZfv86x1RsGArs2dae418c"
   *             }
   *         }
   *     )
   * ),
   *     @OA\Response(
   *         response=422,
   *         description="Unprocessable entity",
   *         @OA\JsonContent(ref="#/components/schemas/Response422")
   *     )
   * )
   */
  public function __invoke(CreateAuthenticateTokenRequest $request, CreateAuthenticateTokenUseCase $createAuthenticateTokenUseCase): JsonResponse
  {

    $credentials = $request->only('email', 'password');

    $tokenData = $createAuthenticateTokenUseCase->execute(
      input: new CreateAuthenticateTokenInputDTO(email: $credentials['email'], password: $credentials['password'])
    );

    return ApiAdapter::toJson(
      data: $tokenData,
      statusCode: Response::HTTP_OK
    );

  }
}
