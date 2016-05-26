<?php

use Illuminate\Support\Collection;

class SimpleResultsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /simpleresults
	 *
	 * @return Response
	 */
	public function index()
	{
		return SimpleResult::all();
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /simpleresults/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /simpleresults
	 *
	 * @return Response
	 */
	public function store()
	{
		return SimpleResult::create(Input::all());
	}

	/**
	 * Display the specified resource.
	 * GET /simpleresults/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		return SimpleResult::find($id);
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /simpleresults/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /simpleresults/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$result = SimpleResult::find($id);
		if ($result != null) {
			$result->fill(Input::all());
			if ($result->save()) return $result;
		}
		return NULL;
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /simpleresults/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		return SimpleResult::destroy($id);
	}

	/**
	 * Return JSON object containing data needed for entry of new results.
	 */
	public function export()
	{
		return new Collection(array(
			'events' => Event::allSortById(array('id', 'readable_id', 'short_name', 'name', 'attempts', 'show_average')),
			'rounds' => Round::all(),
			'competitions' => Competition::all(array('id', 'short_name', 'name', 'date', 'events')),
			'users' => User::all(array('id', 'club_id', 'name', 'last_name')),
		));
	}

	public function dataentry()
	{
		Blade::setContentTags('<%', '%>');          // For variables and all things Blade
    	Blade::setEscapedContentTags('<%%', '%%>'); // For escaped data
		return View::make('dataentry.index');
	}

}
