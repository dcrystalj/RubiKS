<?php

class Registration extends Eloquent {

	protected $table = 'registrations';
	public $timestamps = true;
	protected $softDelete = false;

}