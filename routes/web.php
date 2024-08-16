<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StdDocumentController;
use App\Http\Controllers\DocumentoController;

Route::get('/', function () {
    return view('welcome');
});

//Route::view('/documentos','documentos')->name('documentos');
Route::get('/ap', [StdDocumentController::class, 'index'])->name('documentos');
Route::get('/datos', [StdDocumentController::class, 'consultar'])->name('documentos');
//Route::get('/obt-corr', [StdDocumentController::class, 'ObtCorrelativo'])->name('obt-correlativo');
Route::get('/documentos',[DocumentoController::class,'prueba'])->name('documentos_db');
//Route::view('/listar','listar');
Route::get('/listar',[DocumentoController::class,'extraer_documentos'])->name('listar');