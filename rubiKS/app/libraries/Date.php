<?php

class Date {

	public static function parse($d)
	{
		return Carbon\Carbon::createFromFormat('Y-m-d', $d)->format('d. m. Y');
	}

	public static function dateTime($dt, $dateOnly = FALSE)
	{
		$dt = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $dt);
		return $dateOnly ? $dt->format('d. m. Y') : $dt->format('d. m. Y H:i');
	}

	public static function validDate($y, $m, $d)
	{
		$y = (int) $y;
		$m = (int) $m;
		$d = (int) $d;

		return $y >= 1000 && $y <= 9999 && checkdate($m, $d, $y);
	}

	public static function ymdToDate($y, $m, $d)
	{
		$y = (int) $y;
		$m = (int) $m;
		$d = (int) $d;
		if (!self::validDate($y, $m, $d)) return FALSE;

		$year = (string) $y;
		$month = ($m < 10) ? '0' . $m : (string) $m;
		$day = ($d < 10) ? '0' . $d : (string) $d;

		return $year . '-' . $month . '-' . $day;
	}

}