<?php

class Event extends Eloquent {

	protected $table = 'events';
	public $timestamps = false;
	protected $softDelete = false;

	public static $resultTypes = array('single', 'average');

	/**
	 * Hardcoded rule to push 333 (id=2) on top (as 1st element) when using Events::all().
	 */
	public static function all($columns = array('*'))
	{
		return parent::all($columns)->sort(function($a, $b) {
			if ($a->id == 2) return -2;
			if ($b->id == 2) return 2;
			$cmp = strcmp($a->readable_id, $b->readable_id);
			if ($cmp < 0) return -1;
			if ($cmp > 0) return 1;
			return 0;
		});
	}

	public function results()
	{
		return $this->hasMany('Result');
	}

	public function getTimeLimitAttribute($attribute)
	{
		if ($attribute < 60) {
			return $attribute . ' sec';
		} else {
			return ($attribute / 60) . ' min';
		}
	}

	public function setTimeLimitAttribute($value)
	{
		$split = explode(' ', $value);
		if (count($split) != 2) return;
		$multiplier = $split[1] == 'min' ? 60 : 1;
		$this->attributes['time_limit'] = (int) $value * $multiplier;
	}

	public function showAverage()
	{
		return $this->show_average == '1';
	}

	public function nrPerformances()
	{
		return Result::selectRaw('COUNT(DISTINCT(competition_id)) as nr')->where('event_id', $this->id)->first()->nr;
	}

	/*
	 * To ensure compatibility with Administrator package.
	 */
	public function getShowAverageAttribute($attribute)
	{
		return $attribute;
	}

	public static function whereReadableId($id, $events = NULL)
	{
		if ($events === NULL) return self::where('readable_id', $id)->firstOrFail();

		$event = NULL;
		foreach ($events as $lEvent) {
			if ($lEvent->readable_id == $id) {
				$event = $lEvent;
				break;
			}
		}
		if ($event === NULL) return self::whereReadableId($id);
		return $event;
	}

	/**
	 *
	 */
	public function recordQuery()
	{
		return $this->results()->select('results.*')->leftjoin('users', 'results.user_id', '=', 'users.id')->where('nationality', 'SI');
	}

	public function getRecordSingle()
	{
		return $this->recordQuery()
			->orderBy('single', 'asc')
			->orderBy('date', 'asc')
			//->first();
			->where('single', '=', function($query) {
				$query->from('results')->selectRaw('min(single)')->where('event_id', $this->id);
			})
			->get();
	}

	public function getRecordAverage()
	{
		if (!$this->showAverage()) return new Illuminate\Support\Collection;
		return $this->recordQuery()
			->orderBy('average', 'asc')
			->orderBy('date', 'asc')
			->where('average', '=', function($query) {
				$query->from('results')->selectRaw('min(average)')->where('event_id', $this->id);
			})
			->get();
	}

	public static function injectRecords($events)
	{
		foreach ($events as $event) {
			$event->singleRecord = $event->getRecordSingle();
			$event->averageRecord = $event->getRecordAverage();
		}
	}

}
