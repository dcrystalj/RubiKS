<?php

class Help
{
	static $nationalities = [
		'SI' => 'slovensko',
		'HR' => 'hrvaško',
		'RS' => 'srbsko',
		'HU' => 'madžarsko',
		'AT' => 'avstrijsko',
		'IT' => 'italijansko',
		'ES' => 'špansko',
		'FR' => 'francosko',
		'BR' => 'brazilsko',

		'XX' => 'drugo',
	];

	static $countries = [
		'SI' => 'Slovenija',
		'HR' => 'Hrvaška',
		'RS' => 'Srbija',
		'HU' => 'Madžarska',
		'AT' => 'Avstrija',
		'IT' => 'Italija',
		'ES' => 'Španija',
		'FR' => 'Francija',
		'BR' => 'Brazilija',

		'XX' => '/',
	];

	public static function nationality($nid)
	{
		if (array_key_exists($nid, self::$nationalities)) return self::$nationalities[$nid];
		return 'XX';
	}
	
	public static function country($cid)
	{
		if (array_key_exists($cid, self::$countries)) return self::$countries[$cid];
		return $cid;
	}
}