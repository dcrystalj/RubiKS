<?php

class News extends Eloquent {

	protected $table = 'news';
	public $timestamps = true;
	protected $softDelete = false;

	public static function lastFive()
	{
		return self::orderBy('created_at', 'desc')->take(5)->get();
	}

}