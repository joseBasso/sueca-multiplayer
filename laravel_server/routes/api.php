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
Route::middleware('auth:api')->get('users/', 'UserController@getAllUsers');
Route::middleware('auth:api')->delete('users/{id}/delete', 'UserController@delete');
Route::middleware('auth:api')->put('users/{id}/block', 'UserController@toggleBlockUser');


//Auth
Route::post('register', 'UserController@store');
Route::post('login', 'LoginControllerAPI@login');
Route::post('admin/login', 'LoginControllerAPI@loginAdmin');
Route::middleware('auth:api')->post('logout', 'LoginControllerAPI@logout');
Route::middleware('auth:api')->put('users/{id}', 'UserController@update');


//GAMES

Route::post('games', 'GameController@store')->middleware('secret.key');
Route::put('games/{id}/leave', 'GameController@leaveGame')->middleware('secret.key');
Route::get('games/{id}/cancel', 'GameController@cancelGame')->middleware('secret.key');
Route::put('games/{id}/join', 'GameController@joinGame')->middleware('secret.key');
Route::put('games/{id}/start', 'GameController@startGame')->middleware('secret.key');


/*Route::get('games', 'GameControllerAPI@index')->middleware('secret.key');
Route::get('games/lobby', 'GameControllerAPI@lobby')->middleware('secret.key');
Route::get('games/{id}', 'GameControllerAPI@getGame')->middleware('secret.key');
Route::put('games/{id}/startGame', 'GameControllerAPI@startGame')->middleware('secret.key');
*/


// DECKS

Route::get('decks/{id}', 'DeckController@getDeck');

// Platform

Route::middleware('auth:api')->get('platform/email', 'PlatformController@getPlatformEmail');
Route::middleware('auth:api')->put('platform/email', 'PlatformController@updatePlatformEmail');

// Statistics

Route::get('platform/statistics/users', 'PlatformController@userStatistics');
Route::middleware('auth:api')->get('platform/statistics/authed', 'PlatformController@authenticatedUserStatistics');
Route::middleware('auth:api')->get('platform/statistics/userList', 'PlatformController@userList');

