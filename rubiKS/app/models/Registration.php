<?php

class Registration extends Eloquent {

	protected $table = 'registrations';
	public $timestamps = true;
	protected $softDelete = false;

	public function user()
	{
		return $this->belongsTo('User');
	}

	public function competition()
	{
		return $this->belongsTo('Competition');
	}

	public function confirmed()
	{
		return $this->confirmed == '1';
	}

	public function approved()
	{
		return $this->confirmed();
	}

	public function signedUpForEvent($event)
	{
		$events = Competition::getEvents($this->events, TRUE);
		return in_array($event, $events);
	}

	public static function getRegistration($userId, $competitionId)
	{
		return Registration::where('user_id', $userId)->where('competition_id', $competitionId)->first();
	}

	public static function parseSelectedEvents($competition, $data) {
		$competitionEvents = Competition::getEvents($competition->events, TRUE);
        $events = [];
        foreach ($competitionEvents as $event) {
            if (array_key_exists('event_' . $event, $data) AND $data['event_' . $event] == '1') $events[] = $event;
        }
        return implode(' ', $events);
	}

}