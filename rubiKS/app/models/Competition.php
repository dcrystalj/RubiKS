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