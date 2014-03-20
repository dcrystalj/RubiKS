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

}