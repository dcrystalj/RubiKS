<?php

class Message extends Eloquent {

	protected $table = 'messages';
	public $timestamps = true;
	protected $softDelete = false;

}