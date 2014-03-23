<?php

class Registration extends Eloquent {

	protected $table = 'registrations';
	public $timestamps = true;
	protected $softDelete = false;

	public function approved()
	{
		return $this->status == '1';
	}

	public function signedUpForEvent($event)
	{
		$events = Competition::getEvents($this->events, TRUE);
		return in_array($event, $events);
	}

}