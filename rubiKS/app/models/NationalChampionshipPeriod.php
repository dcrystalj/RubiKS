<?php

class NationalChampionshipPeriod extends Eloquent {

	protected $table = 'national_championship_periods';
	public $timestamps = false;
	protected $softDelete = false;
	protected $fillable = array('year', 'start_date', 'end_date', 'min_results');

	public static $minResultsPerPeriod = 4;

	public static function minYear()
	{
		return self::min('year');
	}

}