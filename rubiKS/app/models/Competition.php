<?php

class Competition extends Eloquent {

	protected $table = 'competitions';
	public $timestamps = false;
	protected $softDelete = false;

	public function results()
	{
		return $this->hasMany('Result');
	}

	public function registrations()
	{
		return $this->hasMany('Registration');
	}

	public function approvedRegistrations()
	{
		return $this->registrations()->where('confirmed', '1');	
	}

	public function getDelegates()
	{
		$ids = array($this->delegate1, $this->delegate2, $this->delegate3);
		$result = User::find($ids);

		$_delegates = array();
		foreach ($result as $delegate) $_delegates[$delegate->id] = $delegate;

		$delegates = array();
		foreach ($ids as $id) $delegates[] = $id == NULL ? NULL : $_delegates[$id];
		
		return $delegates;
	}

	public function isFinished()
	{
		return $this->status == -1;
	}

	public function registrationsOpened()
	{
		return $this->status == 1;
	}

	public function registrationsClosed()
	{
		return $this->status == 0;
	}

	public function parse($events)
	{
		$results = array(); // $results[event id][round id][]
		$res = $this->results()->with('user')->orderBy('event_id', 'asc')->orderBy('average', 'asc')->orderBy('single', 'asc')->get();

		foreach ($res as $r) {
			if (!array_key_exists($r->event_id, $results)) $results[$r->event_id] = array();
			if (!array_key_exists($r->round, $results[$r->event_id])) $results[$r->event_id][$r->round] = array();
			$results[$r->event_id][$r->round][] = $r;
		}

		foreach ($results as $eventId => $rounds) {
			foreach ($rounds as $roundId => $round) {
				$rank = 1;
				foreach ($round as $i => $result) {
					$result->round_rank = $rank++;
				}
			}
		}

		// Backwards compatibility - delete events with no results
		$eventsWithResults = array_keys($results);
		$newEvents = array();
		foreach ($events as $event) {
			if (in_array($event->id, $eventsWithResults)) $newEvents[] = $event;
		}

		return array('results' => $results, 'events' => $newEvents);
	}

	public static function getRegisteredUsers($registrations)
	{
		$ids = array();
		foreach ($registrations as $registration) $ids[] = $registration->user_id;
		$_competitors = User::find($ids);

		$competitors = array();
		foreach ($_competitors as $competitor) $competitors[$competitor->id] = $competitor;
		return $competitors;
	}

	public static function getEvents($events, $array = FALSE)
	{
		if ($array) return explode(' ', $events);
		return Event::whereRaw("readable_id IN ('" . implode("','", explode(' ', $events)) . "')")->get();
	}

	public static function getCompetitionByShortName($shortName)
	{
		return Competition::where('short_name', $shortName)->firstOrFail();
	}

	public function getParsedDate()
	{
		return Date::parse($this->attributes['date']);
	}

	public function getYearAttribute()
	{
		return substr($this->attributes['date'], 0, 4);
	}

}