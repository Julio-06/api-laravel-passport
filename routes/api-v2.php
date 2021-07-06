<?php

use App\Http\Controllers\Api\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::post('v1/register', [RegisterController::class, 'store']);