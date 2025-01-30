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
// Rutas pertenecientes a la Api
Route::group(['prefix' => 'api'], function () {
    // Rutas para el controlador de usuarios
    Route::controller(App\Http\Controllers\UserController::class)->group(function () {
        Route::post('/register','register')->middleware('api.checkparams');
        Route::post('/login','login')->middleware('api.checkparams');
        Route::put('/user/update','update')->middleware('api.auth')->middleware('api.checkparams');
        Route::post('/user/upload', 'upload')->middleware('api.auth');
        Route::get('/user/profile/{userId}','profile');
        Route::get('/user/avatar/{fileName}','getImage');
    });
    // Rutas para el controlador de productos
    Route::controller(App\Http\Controllers\PostController::class)->group(function () {


    });
    // Rutas para el controlador de categorias
    Route::resource('category', App\Http\Controllers\CategoryController::class);

});
