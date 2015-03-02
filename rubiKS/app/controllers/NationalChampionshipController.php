<?php

class NationalChampionshipController extends \BaseController {

	public function __construct()
	{
		$this->beforeFilter(function($routes, $request)
        {
            if (count($routes->parameters()) > 0) {
            	$year = (int) $routes->parameters()['one'];

            	if ($year != 'all') {
            		$minYear = NationalChampionshipPeriod::minYear();
            		if ($year > date('Y') OR $year < $minYear) App::abort(404);
            	}
            }
        },
        [ 'only' => [ 'getEvent', 'getFinal' ] ]);
	}

	public function getIndex()
	{
		return $this->getFinal(date('Y'));
	}

	/**
	 * Show yearly comulative total for a given event and year
	 */
	public function getEvent($year = null, $eventId = '333')
	{
		if ($year === null) $year = date('Y');
		$year = (int) $year;

		// Init
		$event = Event::where('readable_id', $eventId)->firstOrFail();
		$resultType = $event->showAverage() ? 'average' : 'single';

		$periods = NationalChampionshipPeriod::where('year', $year)->get();
		list($allResults, $actualPeriods) = NationalChampionship::allResultsAndActualPeriods($year, $event->id, $periods, TRUE);

		// Fetch final event rankings
		$stats = NationalChampionshipStatsEvent::where('year', $year)
			->where('event_id', $event->id)
			->orderBy('rank')
			->with('user')
			->get();

		return View::make('national-championship.show')
			->withEvents(Event::all())
			->withEvent($event)
			->withYear($year)
			->with('resultType', $resultType)
			->withResults($allResults)
			->withPeriods($actualPeriods)
			->with('finalEventStats', $stats);
	}

	/**
	 * Show final results of the national championship (for a given year)
	 */
	public function getFinal($year = null)
	{
		if ($year === null) $year = date('Y');
		$year = (int) $year;

		// Fetch final rankings
		$stats = NationalChampionshipStatsFinal::where('year', $year)
			->orderBy('rank')
			->with('user')
			->get();

		return View::make('national-championship.final')
			->withEvents(Event::all())
			->withStats($stats)
			->withYear($year);
	}

	/**
	 * Re-generate all championship-related data for a given year
	 */
	public function getGenerateAll($year = 'all')
	{
		if (Auth::guest() OR !Auth::user()->can('sudo')) return App::abort(404);

		if ($year == 'all')
			return View::make('national-championship.generateall');

		$status = NationalChampionship::generate($year);
		return View::make('national-championship.generate')->withStatuses($status);
	}

}
