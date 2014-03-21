<?php

class Result extends Eloquent {

	protected $table = 'results';
	public $timestamps = false;
	protected $softDelete = false;

	public function person()
	{
		return $this->belongsTo('User');
	}

	public function event()
	{
		return $this->belongsTo('Event');
	}

	public function competition()
	{
		return $this->belongsTo('Competition');
	}

	public static function parse($t, $event = NULL, $additional = NULL)
	{
		if ($t == '77777777') return 'DNF';
		if ($t == '88888888') return 'DNS';
		if ($t == '99999999') return 'DSQ';

		if ($event === '333FM') {
			return $t . ' potez';
		} elseif ($event === '33310MIN') {
			//return $t . ' kock (' . self::parse($additional) . ')'; // Old format
			$nrCubes = -1 * ((int) substr($t, 0, 3) - 400);
			$time = (int) substr($t, 3);
			//return $t . ' ' . self::format33310MIN($nrCubes, $time); // Testing
			return $nrCubes . ' kock (' . self::parse($time) . ')';
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

	public static function parseAll($results, $event = NULL)
	{
		$results = explode('@', $results);
		foreach ($results as $i => $r) $results[$i] = self::parse($r, $event);
		return $results;
	}

	public static function format33310MIN($nrCubes, $time)
	{
		$a = (string) (400 - $nrCubes);
		$b = (string) $time;
		if (strlen($b) < 5) $b = str_pad($b, 5, '0');
		return ($a . $b);
	}

}