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
    Route::post('/api/register','register');
    Route::post('/api/login','login');


});
Route::controller(App\Http\Controllers\PostController::class)->group(function () {


});
Route::controller(App\Http\Controllers\CategoryController::class)->group(function () {


});

