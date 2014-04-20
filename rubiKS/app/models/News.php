<?php

class News extends Eloquent {

	protected $table = 'news';
	public $timestamps = true;
	protected $softDelete = false;

	public static function lastFive()
	{
		return self::orderBy('created_at', 'desc')->take(5)->get();
	}

	public function getParsedDate()
	{
		return Date::dateTime($this->attributes['created_at']);
	}

	public function getParsedDateShort()
	{
		return Date::dateTime($this->attributes['created_at'], TRUE);
	}

	public function setUrlSlugAttribute($value)
	{
		$this->attributes['url_slug'] = !empty($value) ? $value : Str::slug($this->title);
	}

}