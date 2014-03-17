<?php

class Result extends Eloquent {

	protected $table = 'results';
	public $timestamps = false;
	protected $softDelete = false;

	public function person()
	{
		return $this->belongsTo('User');
	}

	public function event()
	{
		return $this->belongsTo('Event');
	}

	public function competition()
	{
		return $this->belongsTo('Competition');
	}

}