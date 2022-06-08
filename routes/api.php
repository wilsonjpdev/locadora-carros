<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('cliente', \App\Http\Controllers\ClienteController::class);
Route::apiResource('carro', \App\Http\Controllers\CarroController::class);
Route::apiResource('locacao', \App\Http\Controllers\LocacaoController::class);
Route::apiResource('marca', \App\Http\Controllers\MarcaController::class);
Route::apiResource('modelo', \App\Http\Controllers\ModeloController::class);