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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth'], function(){
    Route::post('post/actions', [App\Http\Controllers\PostController::class, 'actions'])->name('post.actions');
});

Auth::routes();


Route::get('post/{id}', [App\Http\Controllers\PostController::class, 'index'])->name('post.view');
