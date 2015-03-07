<?php

class UsersController extends \BaseController {

	public function __construct()
	{
		$this->beforeFilter(function($route, $request)
		{
			if (Input::has('event')) {
				$competitor = $route->parameters()[$route->parameterNames()[0]];
				return $this->getJsonCompetitorsResults($competitor, Input::get('event'));
			}
		}, array('only' => 'show'));
	}

	public function index()
	{
		$users = User::allConfirmed()->orderBy('last_name')->orderBy('name')->get();
		return View::make('competitors.index')->with('users', $users)->with('i', 1);
	}

	public function show($id)
	{
		if (is_numeric($id)) {
			$user = User::find($id);
		} else {
			$user = User::where('club_id', $id);
		}
		$user = $user->firstOrFail();

		$_events = Event::all();
		$events = [];
		$results = [];
		$competitions = [];
		foreach ($_events as $event) {
			$events[$event->readable_id] = $event;

			$single = Result::where('user_id', $user->id)->where('event_id', $event->id)->orderBy('single', 'asc')->orderBy('date', 'asc')->take(1);

			if ($single->count() > 0) {
				$single = $single->first();
				$results[$event['readable_id']]['single'] = $single;
				// Single NR
				$results[$event['readable_id']]['single_nr'] = false;
				if ($single->single_nr) {
					if ($single->event->getRecordSingle()[0]->single == $single->single)
						$results[$event['readable_id']]['single_nr'] = true;
				}


				if ($event->showAverage()) {
					$average = Result::where('user_id', $user->id)
								->where('event_id', $event->id)
								->orderBy('average', 'asc')
								->orderBy('date', 'asc')->take(1);
					if ($average->count() > 0) {
						$average = $average->first();
						$results[$event->readable_id]['average'] = $average;
						// Average NR
						$results[$event->readable_id]['average_nr'] = false;
						if ($average->average_nr) {
							if ($average->event->getRecordAverage()[0]->average == $average->average)
								$results[$event->readable_id]['average_nr'] = true;
						}
					}
				}
			}
		}

		list($finalMedals, $eventMedals) = $user->getMedals();
		$stats = $user->getStats();

		return View::make('competitors.show')
			->with('user', $user)
			->with('events', $events)
			->with('results', $results)
			->with('stats', $stats)
			->with('finalMedals', $finalMedals)
			->with('eventMedals', $eventMedals);
	}

	public function clubMembers()
	{
		$year = date("Y");
		if (date("n") == 1) $year--; // date("n") = month (1-12)
		$members = User::where('membership_year', '>=', $year)->get();
		$members = $members->sortBy(function($member) {
			return $member->fullName;
		});
		return View::make('competitors.clubmembers')->with('members', $members);
	}

	public function getJsonCompetitorsResults($clubId, $event)
	{
		$event = Event::whereReadableId($event);
		$competitor = User::where('club_id', $clubId)->firstOrFail();
		$results = Result::where('user_id', $competitor->id)->where('event_id', $event->id)->get();

		$data = array(
			'event' => $event->readable_id,
			'event_name' => $event->name,
			'competitor' => $competitor->full_name,
			'single' => array(),
			'date' => array(),
		);
		if ($event->showAverage()) $data['average'] = array();

		foreach ($results as $result) {
			$data['single'][] = (int) $result->single;
			if ($event->showAverage()) $data['average'][] = (int) $result->average;
			$data['date'][] = $result->date;
		}

		return Response::json($data);
	}

}
