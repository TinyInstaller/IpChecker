<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/popup', [App\Http\Controllers\PopupController::class, 'index']);
Route::get('/{ip?}', [App\Http\Controllers\HomeController::class, 'index']);
