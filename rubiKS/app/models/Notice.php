<?php

class Notice extends Eloquent {

	protected $table = 'notices';
	public $timestamps = true;
	protected $softDelete = false;
	
}