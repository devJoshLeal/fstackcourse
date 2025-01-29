<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::controller(App\Http\Controllers\UserController::class)->group(function () {
    Route::post('/api/register','register')->middleware('api.checkparams');
    Route::post('/api/login','login')->middleware('api.checkparams');
    Route::put('/api/user/update','update')->middleware('api.auth')->middleware('api.checkparams');
    Route::post('/api/user/upload', 'upload')->middleware('api.auth');
});
Route::controller(App\Http\Controllers\PostController::class)->group(function () {


});
Route::controller(App\Http\Controllers\CategoryController::class)->group(function () {


});

