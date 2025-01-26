<?php

use App\Http\Controllers\Api\v1\DailyExtraction\CreateDailyExtractionController;
use Illuminate\Support\Facades\Route;

Route::post('/', CreateDailyExtractionController::class);
