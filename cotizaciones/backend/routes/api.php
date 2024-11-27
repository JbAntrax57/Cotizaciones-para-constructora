<?php

use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\MaterialController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\QuoteServiceController;
use App\Http\Controllers\Api\QuoteMaterialController;
use Illuminate\Support\Facades\Route;

Route::apiResource('clients', ClientController::class);
Route::apiResource('services', ServiceController::class);
Route::apiResource('materials', MaterialController::class);
Route::apiResource('projects', ProjectController::class);
Route::apiResource('quote-services', QuoteServiceController::class)->only(['update', 'destroy']);
Route::apiResource('quote-materials', QuoteMaterialController::class)->only(['update', 'destroy']);