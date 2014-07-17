<?php

class News extends Eloquent {

	protected $table = 'news';
	public $timestamps = true;
	protected $softDelete = false;

	public static function lastFive()
	{
		$sticky = self::where('hidden', '0')->where('sticky', '1')->orderBy('created_at', 'desc')->get();
		$lastFive = self::where('hidden', '0')->where('sticky', '0')->orderBy('created_at', 'desc')->take(5)->get();
		return $sticky->merge($lastFive);
	}

	public static function allNotHidden()
	{
		return self::where('hidden', '0')->orderBy('created_at', 'desc');
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