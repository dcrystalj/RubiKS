<?php

class CompetitionsController extends \BaseController {

	public function index($without = FALSE)
	{
		if ($without === FALSE) {
			$competitions = Competition::orderBy('date', 'desc')->get();
		} else {
			$competitions = Competition::where('status', '<', '1')->orderBy('date', 'desc')->get();
		}

		return View::make('competitions.index')->with('competitions', $competitions)->with('competitionNumber', $competitions->count());
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

		$competition = $competition->firstOrFail();

		$delegates = $competition->getDelegates();

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

		foreach ($results as $eventId => $rounds) {
			foreach ($rounds as $roundId => $round) {
				$rank = 1;
				foreach ($round as $i => $result) {
					$result->round_rank = $rank++;
				}
			}
		}

		return View::make('competitions.show')
						->with('competition', $competition)
						->with('delegate1', $delegates[0])->with('delegate2', $delegates[1])->with('delegate3', $delegates[2])
						->with('events', $events)
						->with('results', $results)
						->with('competitors', $competitors);
	}
}