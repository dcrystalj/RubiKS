<?php

class DelegatesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$delegates = Delegate::all()->sortBy('degree');
		$users = Delegate::getUserData($delegates);

		return View::make('delegates.index')->with('delegates', $delegates)->with('users', $users);
	}

}