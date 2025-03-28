<?php

use App\Http\Controllers\MedicionWebController;
use App\Http\Controllers\TxtController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MedicionWebController::class, 'home'])->name('mediciones_index');
Route::get('/mediciones', [MedicionWebController::class, 'index'])->name('get.mediciones');
Route::get('procesar/txt', [TxtController::class, 'showFormTxt']);
Route::post('/process-txt', [TxtController::class, 'processTxtFile'])->name('process-txt');
