<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'v1'
], function($router) {
    Route::apiResource('cliente', 'App\Http\Controllers\ClienteController');
    Route::apiResource('carro', 'App\Http\Controllers\CarroController');
    Route::apiResource('locacao', 'App\Http\Controllers\LocacaoController');
    Route::apiResource('marca', 'App\Http\Controllers\MarcaController');
    Route::apiResource('modelo', 'App\Http\Controllers\ModeloController');
    
    Route::post('/me', 'App\Http\Controllers\AuthController@me');
    Route::post('/refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::post('/logout', 'App\Http\Controllers\AuthController@logout');
});

Route::post('/login', 'App\Http\Controllers\AuthController@login');