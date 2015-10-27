<?php

class CreditsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$credits = Credit::orderBy('id', 'desc')->get();
		return View::make('credits.index')->with('credits', $credits);
	}

}
