<?php

namespace App\Adapter;

use App\Http\Resources\DefaultResource;
use Illuminate\Http\JsonResponse;

class ApiAdapter
{
  public static function toJson(object $data, int $statusCode = 200):JsonResponse
    {
        return (new DefaultResource($data))
                ->response()
                ->setStatusCode($statusCode);
    }
}