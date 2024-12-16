<?php

use App\Http\Controllers\MedicionWebController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'index')->name('mediciones_index');
Route::get('/mediciones', [MedicionWebController::class, 'index'])->name('get.mediciones');