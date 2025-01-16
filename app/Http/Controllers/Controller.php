<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *   version="1.0.0",
 *   title="PriceHub"
 * )
 * @OA\Schema(
 *     schema="Response401",
 *     @OA\Property(property="message", type="string", example="Unauthorized")
 * ),
 * @OA\Schema(
 *     schema="Response422",
 *     type="object",
 *     @OA\Property(property="message", type="string", example=""),
 *     @OA\Property(property="errors", type="object", example={})
 * )
 */
abstract class Controller
{
  //
}
