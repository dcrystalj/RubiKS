<?php

class RankingsController extends \BaseController {

	public function index()
	{
		$events = Event::all();
		return View::make('rankings.index')->with('events', $events);
	}

	public function show($id)
	{
		$resultType = Input::get('type');
		if (!in_array($resultType, Event::$resultTypes)) $resultType = Event::$resultTypes[0];

		$events = Event::all();
		$event = null;
		foreach ($events as $lEvent) {
			if ($lEvent->readable_id == $id) {
				$event = $lEvent;
				break;
			}
		}
		if ($event === null) App::abort(404);
		if (!$event->showAverage() AND $resultType == 'average') $resultType = Event::$resultTypes[0];

		$results = Result::getResultsByEvent($event, $resultType);
		Result::injectRanks($results, $resultType);

		return View::make('rankings.show')->with('events', $events)->with('event', $event)->with('results', $results)->with('resultType', $resultType);
	}

}