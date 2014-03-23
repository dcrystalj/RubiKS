<?php

class CompetitionsController extends \BaseController {

	public function index()
	{
		$competitions = Competition::orderBy('date', 'desc')->get();
		return View::make('competitions.index')->with('competitions', $competitions);
	}

	public function indexFinished()
	{
		$competitions = Competition::where('status', '-1')->orderBy('date', 'desc')->get();
		return View::make('competitions.index')->with('competitions', $competitions);
	}

	public function indexFuture()
	{
		$competitions = Competition::where('status', '>', '-1')->orderBy('date', 'desc')->get();
		return View::make('competitions.index')->with('competitions', $competitions);
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

		if ($competition->isFinished()) {
			$parsed = $competition->parse($events);
			$results = $parsed['results'];
			$competitors = $parsed['competitors'];
			$events = $parsed['events'];
			$registrations = array();
		} else {
			$results = array();
			$registrations = $competition->approvedRegistrations()->get();
			$competitors = Competition::getRegisteredUsers($registrations);
		}

		return View::make('competitions.show')
						->with('competition', $competition)
						->with('delegate1', $delegates[0])->with('delegate2', $delegates[1])->with('delegate3', $delegates[2])
						->with('events', $events)
						->with('results', $results)
						->with('competitors', $competitors)
						->with('registrations', $registrations);
	}
}