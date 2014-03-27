<?php

class Event extends Eloquent {

	protected $table = 'events';
	public $timestamps = false;
	protected $softDelete = false;

	public static $resultTypes = array('single', 'average');

	public function results()
	{
		return $this->hasMany('Result');
	}

	public function getTimeLimitAttribute()
	{
		if ($this->attributes['time_limit'] < 60) {
			return $this->attributes['time_limit'] . ' sec';
		} else {
			return ($this->attributes['time_limit'] / 60) . ' min';
		}
	}

	public function showAverage()
	{
		return $this->show_average == '1';
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

	public function getRecordSingle()
	{
		$result = $this->results()->orderBy('single', 'asc')->take(1);
		if ($result->count() < 1) return NULL; // This event does not have any results yet!
		return $result->first();
	}

	public function getRecordAverage()
	{
		if (!$this->showAverage()) return NULL;

		$result = $this->results()->orderBy('average', 'asc')->take(1);
		if ($result->count() < 1) return NULL;
		return $result->first();
	}

	public static function injectRecords($events)
	{
		foreach ($events as $event) {
			$event->singleRecord = $event->getRecordSingle();
			$event->averageRecord = $event->getRecordAverage();
		}
	}

}