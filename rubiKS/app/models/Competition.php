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
		$competitors = array();
		$res = $this->results()->orderBy('event_id', 'asc')->orderBy('average', 'asc')->orderBy('single', 'asc')->get();

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

		// Backwards compatibility - delete events with no results
		$eventsWithResults = array_keys($results);
		$newEvents = array();
		foreach ($events as $event) {
			if (in_array($event->id, $eventsWithResults)) $newEvents[] = $event;
		}

		return array('results' => $results, 'competitors' => $competitors, 'events' => $newEvents);
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

}