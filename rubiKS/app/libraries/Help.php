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
		'UA' => 'ukrajinsko',
		'RU' => 'rusko',

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
		'UA' => 'Ukrajina',
		'RU' => 'Rusija',

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

	public static function formatChampionshipPoints($points)
	{
		return number_format($points, 2, ".", " ");
	}

	/**
	 * Medals: http://findicons.com/search/medal - licensed under "Commercial-use" and "No Link Required"
	 */
	public static function medal($rank)
	{
		if ($rank > 3) $rank = 'x';
		return '<img width="20" src="' . asset('assets/medals/medal-' . $rank . '.png') . '">';
	}

	public static function checked($bool, $bold = FALSE)
	{
		if ($bool) return $bool ? '✔' : '✘';
		return $bool ? '✓' : '✗';
	}

}
