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
		$rounds = Round::all();
		$allRounds = array();
		foreach ($rounds as $round) $allRounds[$round->id] = $round;

		$results = array(); // $results[event id][round id][]
		$res = $this->results()->with('user')->orderBy('event_id', 'asc')->orderBy('average', 'asc')->orderBy('single', 'asc')->get();

		foreach ($res as $r) {
			if (!array_key_exists($r->event_id, $results)) $results[$r->event_id] = array();
			if (!array_key_exists($r->round_id, $results[$r->event_id])) $results[$r->event_id][$r->round_id] = array();
			$results[$r->event_id][$r->round_id][] = $r;
		}

		foreach ($results as $eventId => $rounds) {
			// Sort rounds by sort_key
			$results[$eventId] = array_sort($results[$eventId], function($value) use ($allRounds) {
				return $allRounds[$value[0]->round_id]->sort_key;
			});

			// Generate an extra round with best results from each competitor
			if (count($results[$eventId]) > 1) {
				$finalRound = array();
				foreach ($res as $r) {
					if ($r->event_id != $eventId) continue;

					if (!array_key_exists($r->user_id, $finalRound)) {
						$finalRound[$r->user_id] = $r->replicate();
					}

					// Check whether a better result exists
					if ($r->single < $finalRound[$r->user_id]->single) $finalRound[$r->user_id]->single = $r->single;
					if ($r->average < $finalRound[$r->user_id]->average) {
						$finalRound[$r->user_id]->average = $r->average;
						$finalRound[$r->user_id]->results = $r->results;
					}
				}

				// Re-sort
				usort($finalRound, function($a, $b) {
					if ($a->average == $b->average) {
						if ($a->single == $b->single) return 0;
						return $a->single > $b->single ? 1 : -1;
					}
					return $a->average > $b->average ? 1 : -1;
				});
				$results[$eventId][Round::DEFAULT_FINAL_ROUND_ID] = $finalRound;
			}
		}

		// Calculate ranks for each round
		foreach ($results as $eventId => $rounds) {
			foreach ($rounds as $roundId => $round) {
				$rank = 0;
				$i = 0;
				$previous = array('single' => null, 'average' => null);
				foreach ($round as $j => $result) {
					if ($result->average == $previous['average'] AND $result->single == $previous['single']) {
						$result->round_rank = $rank;
						$i++;
					} else {
						$rank++;
						$result->round_rank = $rank + $i;
						$i = 0;
					}
					$previous = $result;
				}
			}
		}

		// Backwards compatibility - add events even if they're not in `events` column.
		$events = Event::all();

		// Backwards compatibility - delete events with no results
		$eventsWithResults = array_keys($results);
		$newEvents = array();
		foreach ($events as $event) {
			if (in_array($event->id, $eventsWithResults)) $newEvents[] = $event;
		}

		return array($results, $newEvents, $allRounds);
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
