<?php

class RankingsController extends \BaseController {

	public function __construct()
	{
		$this->beforeFilter(function()
			{
				if (!in_array(Input::get('type'), Event::$resultTypes)) App::abort(404);
			}, 
			array('only' => 'show')
		);
	}

	public function index()
	{
		$events = Event::all();
		return View::make('rankings.index')->with('events', $events);
	}

	public function show($id)
	{
		$resultType = Input::get('type');

		$events = Event::all();
		$event = Event::whereReadableId($id, $events);

		if (!$event->showAverage() AND $resultType == 'average') $resultType = Event::$resultTypes[0];

		$results = Result::getResultsByEvent($event, $resultType);
		Result::injectRanks($results, $resultType);

		return View::make('rankings.show')->with('events', $events)->with('event', $event)->with('results', $results)->with('resultType', $resultType);
	}

}