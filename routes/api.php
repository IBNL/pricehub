<?php

use App\Http\Controllers\Api\v1\Brand\BrandCreateBatchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*Route::get('/user', function (Request $request) {
  return $request->user();
})->middleware('auth:sanctum');
*/

$pathV1 = 'routes/v1';

Route::prefix('v1')->group(function () use($pathV1){
  Route::prefix('brand')->group(base_path($pathV1 . '/BrandRoute.php'));
});
