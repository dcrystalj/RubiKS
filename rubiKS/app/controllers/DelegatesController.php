<?php

class DelegatesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$delegates = Delegate::with('user')->get();
		$delegates = Delegate::injectAdditionalDataAndSort($delegates);

		return View::make('delegates.index')->with('delegates', $delegates);
	}

	public function user()
	{
		return $this->belongsTo('User');
	}

}