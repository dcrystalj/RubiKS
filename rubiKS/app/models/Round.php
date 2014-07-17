<?php

class Round extends Eloquent {

	public $timestamps = false;
	protected $softDelete = false;
	protected $fillable = array('name', 'short_name', 'sort_key');

	const DEFAULT_ROUND_ID = 1;
	const DEFAULT_FINAL_ROUND_ID = 2;
}