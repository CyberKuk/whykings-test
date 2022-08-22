<?php

use App\Http\Controllers\PersonController;
use Illuminate\Support\Facades\Route;

Route::get('/person', [PersonController::class, 'list']);
Route::post('/person', [PersonController::class, 'create']);
