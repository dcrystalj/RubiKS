<?php

class HistoryOfNRController extends \BaseController {
	
	public function getIndex()
	{
		return $this->getEvent('333');
	}

	public function getEvent($eventReadableId)
	{
		$events = Event::all();
		$event = Event::whereReadableId($eventReadableId);

		$single = Result::where('event_id', $event->id)
			->where('single_nr', '1')
			->orderBy('date', 'desc')
			->with('user')
			->with('competition')
			->get();

		$average = Result::where('event_id', $event->id)
			->where('average_nr', '1')
			->orderBy('date', 'desc')
			->with('user')
			->with('competition')
			->get();

		$results = array('average' => $average, 'single' => $single);

		return View::make('history-of-nr.show')
			->withEvents($events)
			->withEvent($event)
			->withCountry('SI')
			->with('allResults', $results);
	}

}