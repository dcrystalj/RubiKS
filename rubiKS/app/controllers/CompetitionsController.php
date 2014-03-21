<?php

class CompetitionsController extends \BaseController {

	public function index($without = FALSE)
	{
		if ($without === FALSE) {
			$competitions = Competition::orderBy('date', 'desc')->get();
		} else {
			$competitions = Competition::where('status', '<', '1')->orderBy('date', 'desc')->get();
		}

		return View::make('competitions.index')->with('competitions', $competitions)->with('i', $competitions->count());
	}

	public function indexWithout()
	{
		return $this->index(TRUE);
	}

	public function show($id)
	{
		if (is_numeric($id)) {
			$competition = Competition::find($id);
		} else {
			$competition = Competition::where('short_name', $id);
		}

		if ($competition->count() < 1) App::abort(404);
		$competition = $competition->first();

		$delegate1 = Competition::getDelegate($competition->delegate1);
		$delegate2 = Competition::getDelegate($competition->delegate2);
		$delegate3 = Competition::getDelegate($competition->delegate3);

		$events = Competition::getEvents($competition->events);

		// Check competition's status
		
		$results = array(); // $results[event id][round id][]
		$competitors = array();
		$res = $competition->results()->orderBy('event_id', 'asc')->orderBy('average', 'asc')->orderBy('single', 'asc')->get();

		foreach ($res as $r) {
			if (!array_key_exists($r->event_id, $results)) $results[$r->event_id] = array();
			if (!array_key_exists($r->round, $results[$r->event_id])) $results[$r->event_id][$r->round] = array();
			$results[$r->event_id][$r->round][] = $r;

			if (!array_key_exists($r->user_id, $competitors)) $competitors[$r->user_id] = User::find($r->user_id);
		}

		return View::make('competitions.show')
						->with('competition', $competition)
						->with('d1', $delegate1)->with('d2', $delegate2)->with('d3', $delegate3)
						->with('events', $events)
						->with('results', $results)
						->with('competitors', $competitors);
	}
}