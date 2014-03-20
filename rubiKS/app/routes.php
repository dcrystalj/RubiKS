<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', 'NewsController@index');

Route::get('news/archive', 'NewsController@archive');
Route::resource('news', 'NewsController');

Route::get('competitions/without', 'CompetitionsController@indexWithout');
Route::resource('competitions', 'CompetitionsController');

App::missing(function($exception)
{
    return Response::view('errors.missing', array(), 404);
});