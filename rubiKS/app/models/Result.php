<?php

class Result extends Eloquent {

	protected $table = 'results';
	public $timestamps = false;
	protected $softDelete = false;

	public static $nonNumericalResults = array(
		'77777777' => 'DNF',
		'88888888' => 'DNS',
		'99999999' => 'DSQ',
	);

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

	public function round()
	{
		return $this->belongsTo('Round');
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
		// DNF, DNS, DSQ
		if (array_key_exists($t, self::$nonNumericalResults)) return self::$nonNumericalResults[$t];

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
		if (strlen($b) < 5) $b = str_pad($b, 5, '0', STR_PAD_LEFT);
		return ($a . $b);
	}

	public static function getResultsByEvent($event, $resultType)
	{
		$_results = Result::select('user_id', DB::Raw("MIN(" . $resultType . ") as 'best_result'"))
							->where('event_id', $event->id)
							->groupBy('user_id')
							->orderBy('best_result', 'asc')
							->get();

		$results = array();
		foreach ($_results as $result) {
			$results[] = Result::where('event_id', $event->id)
								->where('user_id', $result->user_id)
								->where($resultType, $result->best_result)
								->orderBy('date', 'asc')
								->firstOrFail();
		}

		return $results;
	}

	public static function injectRanks($results, $resultType)
	{
		$rank = 0;
		$gap = 0;
		$previousResult = -1;

		foreach ($results as $result) {
			if ($previousResult == $result[$resultType]) {
				$gap++;
			} else {
				if ($gap > 0) {
					$rank += $gap;
					$gap = 0;
				}
				$rank++;
			}
			$result->injectedRank = $rank;
			$previousResult = $result[$resultType];
		}

		return $results;
	}

	public static function dnfNumericalValue()
	{
		return min(array_map(function($x) { return (int) $x; }, array_keys(self::$nonNumericalResults)));
	}

	public static function dnsNumericalValue()
	{
		return array_keys(Result::$nonNumericalResults)[1];
	}

}