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

	public static function getDelegate($id)
	{
		if (NULL == $id) return NULL;
		return User::find($id);
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

}