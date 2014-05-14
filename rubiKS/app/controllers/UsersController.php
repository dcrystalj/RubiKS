<?php

class UsersController extends \BaseController {

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
				$results[$event['readable_id']]['single'] = $single->first();

				if ($event->showAverage()) {
					$average = Result::where('user_id', $user->id)
								->where('event_id', $event->id)
								->orderBy('average', 'asc')
								->orderBy('date', 'asc')->take(1);
					if ($average->count() > 0) {
						$results[$event->readable_id]['average'] = $average->first();
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

}