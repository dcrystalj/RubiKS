<?php

class Event extends Eloquent {

	protected $table = 'events';
	public $timestamps = false;
	protected $softDelete = false;

	public function results()
	{
		return $this->hasMany('Result');
	}

}