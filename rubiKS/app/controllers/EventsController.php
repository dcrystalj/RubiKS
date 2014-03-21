<?php

class EventsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$events = Event::all();

		return View::make('events.index')->with('events', $events);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$event = Event::where('readable_id', $id);
		if ($event->count() < 1) App::abort(404);
		$event = $event->first();

		$single = Result::where('event_id', $event->id)->take(1)->orderBy('single', 'asc')->firstOrFail();

		if ($event->show_average) {
			$average = $event->results()->take(1)->orderBy('average', 'asc')->firstOrFail();
		} else {
			$average = NULL;
		}

		return View::make('events.show')->with('event', $event)->with('single', $single)->with('average', $average);
	}

	/**
	 * Show the form for editing the specified resource.
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
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}