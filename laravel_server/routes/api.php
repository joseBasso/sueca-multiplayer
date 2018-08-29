<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Auth
Route::post('register', 'UserController@store');
Route::post('login', 'LoginControllerAPI@login');
Route::post('admin/login', 'LoginControllerAPI@loginAdmin');
Route::middleware('auth:api')->post('logout', 'LoginControllerAPI@logout');
Route::middleware('auth:api')->put('users/{id}', 'UserController@update');


//GAMES

Route::post('games', 'GameController@store')->middleware('secret.key');

/*Route::get('games', 'GameControllerAPI@index')->middleware('secret.key');
Route::get('games/lobby', 'GameControllerAPI@lobby')->middleware('secret.key');
Route::get('games/{id}', 'GameControllerAPI@getGame')->middleware('secret.key');
Route::put('games/{id}/startgame', 'GameControllerAPI@startGame')->middleware('secret.key');
Route::get('games/{id}/cancelGame', 'GameControllerAPI@cancelGame')->middleware('secret.key');
Route::put('games/{id}/joinGame', 'GameControllerAPI@joinGame')->middleware('secret.key');
Route::put('games/{id}/leaveGame', 'GameControllerAPI@leaveGame')->middleware('secret.key');
Route::put('games/{id}/startGame', 'GameControllerAPI@startGame')->middleware('secret.key');
