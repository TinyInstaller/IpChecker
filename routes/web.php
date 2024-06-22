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
Route::prefix('popup')->group(function(){
    Route::get('/', [App\Http\Controllers\PopupController::class, 'index']);
    Route::get('js', [App\Http\Controllers\PopupController::class, 'getJs']);
    Route::get('css', [App\Http\Controllers\PopupController::class, 'getCss']);
});
Route::get('/{ip?}', [App\Http\Controllers\HomeController::class, 'index']);
