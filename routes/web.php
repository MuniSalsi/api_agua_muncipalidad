<?php

use App\Http\Controllers\MedicionWebController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MedicionWebController::class, 'index'])->name('mediciones_index');