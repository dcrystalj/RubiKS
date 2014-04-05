<?php

class CreditsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$credits = Credit::all();
		return View::make('credits.index')->with('credits', $credits);
	}

}