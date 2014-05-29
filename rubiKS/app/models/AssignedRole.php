<?php

class AssignedRole extends Eloquent
{
	public $timestamps = false;
	protected $softDelete = false;
	
	public function role()
	{
		return $this->belongsTo('Role');
	}

	public function user()
	{
		return $this->belongsTo('User');
	}
}