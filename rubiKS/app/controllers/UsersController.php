<?php

class UsersController extends \BaseController {

	public function index()
	{
		$users = User::all()->sortBy('last_name');
		return View::make('competitor.index')->with('users', $users)->with('i', 1);
	}

	public function show($id)
	{
		if (is_numeric($id)) {
			$user = User::find($id);
		} else {
			$user = User::where('club_id', $id);
		}
		if ($user->count() < 1) App::abort(404);
		$user = $user->first();

		$_events = Event::all();
		$events = [];
		$results = [];
		$competitions = [];
		foreach ($_events as $event) {
			$events[$event['readable_id']] = $event;

			$single = Result::where('user_id', $user['id'])->where('event_id', $event['id'])->orderBy('single', 'asc')->orderBy('date', 'asc')->take(1);

			if ($single->count() > 0) {
				$results[$event['readable_id']]['single'] = $single->first();
			}

			if ($event->show_average === '1') {
				$average = Result::where('user_id', $user['id'])
							->where('event_id', $event['id'])
							->orderBy('average', 'asc')
							->orderBy('date', 'asc')->take(1);
				if ($average->count() > 0) {
					$results[$event['readable_id']]['average'] = $average->first();
				}
			}
		}

		return View::make('competitor.show')->with('user', $user)->with('events', $events)->with('results', $results);
	}
}