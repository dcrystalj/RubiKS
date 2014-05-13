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
		return $this->getEvent(date('Y'), '333');
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
		list($allResults, $actualPeriods) = NationalChampionship::allResultsAndActualPeriods($year, $event,$periods, TRUE);

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
		// Auth

		if ($year == 'all') return View::make('national-championship.generateall');

		$year = (int) $year;
		$events = Event::all();

		// Generate ranks (for all periods) for all events
		$status1 = array();
		foreach ($events as $event) {
			if (NationalChampionship::generateRanks($year, $event->readable_id) === True) {
				$status[] = $year . ' ' . $event->readable_id . ': success!';
			} else {
				$status[] = $year . ' ' . $event->readable_id. ': <b>fail</b>!';
			}
		}

		// Generate final ranks
		$status3 = array();
		if (NationalChampionship::generateStatsFinal($year) === True) {
			$status3[] = $year . ' ' . 'final' . ': success!';
		} else {
			$status3[] = $year . ' ' . 'final' . ': <b>fail</b>!';
		}

		// Generate yearly stats for all events
		$status2 = array();
		foreach ($events as $event) {
			if (NationalChampionship::generateStatsEvent($year, $event->readable_id) === True) {
				$status2[] = $year . ' ' . 'e' . $event->readable_id . ': success!';
			} else {
				$status2[] = $year . ' ' . 'e' . $event->readable_id . ': <b>fail</b>!';
			}
		}

		return implode('<br>' . PHP_EOL, array_merge($status, $status2, $status3)) . '<br> <b>Done!</b>';
	}

}