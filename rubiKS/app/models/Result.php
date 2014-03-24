<?php

class Result extends Eloquent {

	protected $table = 'results';
	public $timestamps = false;
	protected $softDelete = false;

	public function user()
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

	public function isSingleNR()
	{
		return $this->single_nr == '1';
	}

	public function isSinglePB()
	{
		return $this->single_pb == '1';
	}

	public function isAverageNR()
	{
		return $this->average_nr == '1';
	}

	public function isAveragePB()
	{
		return $this->average_pb == '1';
	}

	public static function parse($t, $event = NULL)
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
		foreach ($results as $i => $r) $results[$i] = (int) $r;
		$gotMin = FALSE;
		$gotMax = FALSE;
		$final = [];
		foreach ($results as $i => $r) {
			$exclude = FALSE;
			if (!$gotMin AND $r === min($results)) {
				$exclude = TRUE;
				$gotMin = TRUE;
			}
			if (!$gotMax AND $r === max($results)) {
				$exclude = TRUE;
				$gotMax = TRUE;
			}

			$final[] = array('t' => self::parse($r, $event), 'exclude' => $exclude);
		}

		return $final;
	}

	public static function parseAllString($results, $event)
	{
		$parsed = self::parseAll($results, $event);
		$array = array();
		foreach ($parsed as $i => $result) {
			$array[] = $result['exclude'] ? "[" . $result['t'] . "]" : $result['t'];
		}
		return implode(", ", $array);
	}

	public static function format33310MIN($nrCubes, $time)
	{
		$a = (string) (400 - $nrCubes);
		$b = (string) $time;
		if (strlen($b) < 5) $b = str_pad($b, 5, '0');
		return ($a . $b);
	}

}