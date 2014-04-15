<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	if (App::environment('local')) {
		DB::listen(function($sql, $bindings, $time)
		{
			var_dump($sql);
			//var_dump($bindings);
		});	
	}
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::guest('login');
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});


/*
|--------------------------------------------------------------------------
| Registrations Filters
|--------------------------------------------------------------------------
*/

Route::filter('registrations.createStore', function($routes, $request)
{
	$competitionShortName = Input::old('competition') == null ? Input::get('competition') : Input::old('competition');
	if (is_null($competitionShortName)) return Redirect::to('registrations');

	$competition = Competition::getCompetitionByShortName($competitionShortName); // Competition exists
	if (!$competition->registrationsOpened()) return Redirect::to('registrations'); // Registrations are opened

	// Making sure that user IS NOT already registered to this competition
	$registration = Registration::getRegistration(Auth::user()->id, $competition->id);
	if ($registration !== null) return Redirect::to('registrations/show', $competitionShortName);
});

Route::filter('registrations.editUpdate', function($routes, $request)
{
	if (count($routes->parameters()) < 1) App::abort(404);
	$competitionShortName = $routes->parameters()['registrations'];

	$competition = Competition::getCompetitionByShortName($competitionShortName); // Competition exists
	if (!$competition->registrationsOpened()) return Redirect::to('registrations'); // Registrations are opened

	// Making sure that user IS already registered to this competition
	$registration = Registration::getRegistration(Auth::user()->id, $competition->id);
	if ($registration === null) return Redirect::to('registrations');
});
