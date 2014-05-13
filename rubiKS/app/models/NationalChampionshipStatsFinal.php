<?php

class NationalChampionshipStatsFinal extends \Eloquent {

	protected $table = 'national_championship_stats_final';
	public $timestamps = false;
	protected $softDelete = false;
	protected $fillable = ['year', 'user_id', 'rank', 'score', 'details'];

	public function user()
	{
		return $this->belongsTo('User');
	}

}