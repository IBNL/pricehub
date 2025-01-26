<?php

use Illuminate\Support\Facades\Route;

/*Route::get('/user', function (Request $request) {
  return $request->user();
})->middleware('auth:sanctum');
*/

$pathV1 = 'routes/v1';

Route::prefix('v1')->group(function () use($pathV1){
  Route::prefix('brand')->group(base_path($pathV1 . '/BrandRoute.php'));
  Route::prefix('daily-extraction')->group(base_path($pathV1 . '/DailyExtractionRoute.php'));

});
