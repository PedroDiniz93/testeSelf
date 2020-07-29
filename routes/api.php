<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/* Player Routers */
Route::post('/players-create', 'PlayerController@create');
Route::put('/players-update/{id}', 'PlayerController@update');
Route::delete('/players-destroy/{id}', 'PlayerController@destroy');
Route::get('/player/{id}', 'PlayerController@show');
Route::get('/players-verify', 'PlayerController@verifyTeam');
Route::get('/players', 'PlayerController@index');
Route::get('/orderned', 'PlayerController@ordernedTeams');
/* Team Routers */
Route::post('/teams-create', 'TeamController@create');
Route::put('/teams-update/{id}', 'TeamController@update');
Route::delete('/teams-destroy/{id}', 'TeamController@destroy');
Route::get('/team/{id}', 'TeamController@show');
Route::get('/teams', 'TeamController@index');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
