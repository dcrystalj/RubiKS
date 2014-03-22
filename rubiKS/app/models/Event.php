<?php

class Event extends Eloquent {

	protected $table = 'events';
	public $timestamps = false;
	protected $softDelete = false;

	public function results()
	{
		return $this->hasMany('Result');
	}

	public function getTimeLimitAttribute()
	{
		if ($this->attributes['time_limit'] < 60) {
			return $this->attributes['time_limit'] . ' sec';
		} else {
			return ($this->attributes['time_limit'] / 60) . ' min';
		}
	}

	public function showAverage()
	{
		return $this->show_average == '1';
	}

}