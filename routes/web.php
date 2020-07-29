<?php

use Illuminate\Support\Facades\Route;

/*
Route::resource('teams', 'TeamController');
Route::post('/players-create', 'PlayerController@store')->name('players.store');
Route::get('/teams', 'TeamController@index')->name('teams.index');
Route::get('/teams/{id}', 'TeamController@show')->name('teams.show');
Route::get('/teams/{id}/edit', 'TeamController@edit')->name('teams.edit');
Route::get('/teams/create', 'TeamController@create')->name('teams.create');
Route::post('/teams', 'TeamController@store')->name('teams.store');
Route::put('/teams/{id}', 'TeamController@update')->name('teams.update');
Route::delete('/teams/{id}', 'TeamController@destroy')->name('teams.destroy');
*/
Route::get('/', function () {
    return view('welcome');
});
