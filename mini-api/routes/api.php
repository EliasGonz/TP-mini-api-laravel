<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ReclutaController;

Route::get('/reclutados', [ReclutaController::class, 'getAll']);
Route::post('/recluta', [ReclutaController::class, 'store']);

