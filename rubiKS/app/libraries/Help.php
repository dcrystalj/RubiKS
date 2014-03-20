<?php

class Help
{
	public static function date($d)
	{
		return Carbon\Carbon::createFromFormat('Y-m-d', $d)->format('d. m. Y');
	}

	public static function dateTime($dt, $dateOnly = FALSE)
	{
		$dt = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $dt);
		return $dateOnly ? $dt->format('d. m. Y') : $dt->format('d. m. Y H:i');
	}

	public static function country($cid)
	{
		switch($cid) {
			case 'SI':
				return 'Slovenija';
				break;
				
			case 'HR':
				return 'Hrvaška';
				break;

			case 'ES':
				return 'Španija';
				break;
				
			case 'FR':
				return 'Francija';
				break;
				
			case 'BR':
				return 'Brazilija';
				break;
				
			default:
				return $cid;
				break;
		}
	}
}