<?php

use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\MaterialController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\QuoteController;
use App\Http\Controllers\Api\QuoteServiceController;
use App\Http\Controllers\Api\QuoteMaterialController;
use App\Http\Controllers\Api\ConstructionCalculatorController;
use App\Http\Controllers\Api\ConstructionTypeMaterialController;
use App\Http\Controllers\Api\ConstructionTypeServiceController;
use Illuminate\Support\Facades\Route;

Route::apiResource('clients', ClientController::class);
Route::apiResource('services', ServiceController::class);
Route::apiResource('materials', MaterialController::class);
Route::apiResource('projects', ProjectController::class);
Route::apiResource('quotes', QuoteController::class);
Route::apiResource('quote-services', QuoteServiceController::class)->only(['update', 'destroy']);
Route::apiResource('quote-materials', QuoteMaterialController::class)->only(['update', 'destroy']);

// Ruta para obtener proyectos por cliente
Route::get('/projects/by-client/{clientId}', [ProjectController::class, 'getByClient']);

// Rutas para el calculador de construcción
Route::post('/calculate-construction', [ConstructionCalculatorController::class, 'calculate']);
Route::get('/construction-types', [ConstructionCalculatorController::class, 'getConstructionTypes']);

// Rutas para configuración de tipos de construcción
Route::get('/construction-types/{constructionType}/materials', [ConstructionTypeMaterialController::class, 'index']);
Route::post('/construction-type-materials', [ConstructionTypeMaterialController::class, 'store']);
Route::get('/construction-type-materials/{constructionTypeMaterial}', [ConstructionTypeMaterialController::class, 'show']);
Route::put('/construction-type-materials/{constructionTypeMaterial}', [ConstructionTypeMaterialController::class, 'update']);
Route::delete('/construction-type-materials/{constructionTypeMaterial}', [ConstructionTypeMaterialController::class, 'destroy']);

Route::get('/construction-types/{constructionType}/services', [ConstructionTypeServiceController::class, 'index']);
Route::post('/construction-type-services', [ConstructionTypeServiceController::class, 'store']);
Route::get('/construction-type-services/{constructionTypeService}', [ConstructionTypeServiceController::class, 'show']);
Route::put('/construction-type-services/{constructionTypeService}', [ConstructionTypeServiceController::class, 'update']);
Route::delete('/construction-type-services/{constructionTypeService}', [ConstructionTypeServiceController::class, 'destroy']);