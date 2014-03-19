<?php

class Help
{
	public static function date($d)
	{
		$d = explode('-', $d);

		if (count($d) < 3) {
			return $d;
		} else {
			return $d[2] . '. ' . $d[1] . '. ' . $d[0];
		}
	}

	public static function dateTime($dt, $dateOnly = FALSE, $smallTime = FALSE)
	{
		$dt = explode(' ', $dt);
		if (count($dt) != 2) return $dt;

		if ($dateOnly) return self::date($dt[0]);
		if ($smallTime) return self::date($dt[0]) . ' <small>' . substr($dt[1], 0, 5) . '</small>';

		return self::date($dt[0]) . ' ' . substr($dt[1], 0, 5);
	}

	public static function format33310MIN($nrCubes, $time)
	{
		$a = (string) (400 - $nrCubes);
		$b = (string) $time;
		if (strlen($b) < 5) $b = str_pad($b, 5, '0');
		return ($a . $b);
	}

	public static function result($t, $event = NULL, $additional = NULL)
	{
		if ($t == '77777777') return 'DNF';
		if ($t == '88888888') return 'DNS';
		if ($t == '99999999') return 'DSQ';

		if ($event === '333FM') {
			return $t . ' potez';
		} elseif ($event === '33310MIN') {
			//return $t . ' kock (' . self::result($additional) . ')'; // Old format
			$nrCubes = -1 * ((int) substr($t, 0, 3) - 400);
			$time = (int) substr($t, 3);
			//return $t . ' ' . self::format33310MIN($nrCubes, $time); // Testing
			return $nrCubes . ' kock (' . self::result($time) . ')';
		}

		$c = $t % 100;
		$s1 = floor($t / 100);
		$s = $s1 % 60;
		$m = floor($s1 / 60);

		if ($c < 10) $c = '0' . $c;
		if ($m < 1) {
			return $s . '.' . $c;
		} else {
			if ($s < 10) $s = '0' . $s;
			return $m . ':' . $s . '.' . $c;
		}
	}

	public static function gender($g, $short = FALSE) {
		if ($short) return $g === 'm' ? 'M' : 'Ž';
		return $g === 'm' ? 'moški' : 'ženski';
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