<?php

use App\Http\Controllers\Api\v1\Brand\BrandCreateBatchController;
use Illuminate\Support\Facades\Route;

Route::post('/create-batch', BrandCreateBatchController::class);
