<?php

use App\Http\Controllers\Api\v1\Authenticate\CreateAuthenticateTokenController;
use Illuminate\Support\Facades\Route;

Route::post('/create-token', CreateAuthenticateTokenController::class);
