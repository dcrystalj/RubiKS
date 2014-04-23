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

Route::get('/', 'NewsController@lastFive');

Route::resource('news', 'NewsController');

Route::resource('notices', 'NoticesController', array('only' => array('index')));

Route::get('members', 'UsersController@clubMembers');
Route::resource('competitors', 'UsersController');

Route::get('competitions/finished', 'CompetitionsController@indexFinished');
Route::get('competitions/future', 'CompetitionsController@indexFuture');
Route::resource('competitions', 'CompetitionsController', array('only' => array('index', 'show')));

Route::resource('rankings', 'RankingsController', array('only' => array('index', 'show')));

Route::get('records', 'EventsController@records');
Route::resource('events', 'EventsController', array('only' => array('index', 'show')));

Route::resource('delegates', 'DelegatesController', array('only' => array('index')));

Route::resource('algorithms', 'AlgorithmsController', array('only' => array('index', 'show')));

Route::resource('credits', 'CreditsController', array('only' => array('index')));

Route::resource('static', 'StaticPagesController', array('only' => array('index', 'show')));

// Confide RESTful route
Route::controller('user', 'UsersConfideController');
Route::get('login', 'UsersConfideController@getLogin');
Route::get('logout', 'UsersConfideController@getLogout');

Route::resource('registrations', 'RegistrationsController');

App::missing(function($exception)
{
    return Response::view('errors.missing', array(), 404);
});