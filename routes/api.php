<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StdDocumentController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/obt-correlativo', [StdDocumentController::class, 'ObtCorrelativo']);
Route::post('/obt-documento', [StdDocumentController::class, 'ObtDocumento']);
Route::post('/regist-documento', [StdDocumentController::class, 'consultar']);
//Route::post('/insertar', [StdDocumentController::class, 'insertar']);