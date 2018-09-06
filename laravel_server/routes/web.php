<?php

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
    return redirect()->route('game');
})->name('home');
Route::get('/game','VueController@game')->name('game');
Route::get('/admin','VueController@admin')->name('admin');

Route::auth();
Route::get('user/activation/{token}', 'UserController@activateUser')->name('user.activate');
