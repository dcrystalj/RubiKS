<?php

class NationalChampionshipStatsEvent extends \Eloquent {

	protected $table = 'national_championship_stats_event';
	public $timestamps = false;
	protected $softDelete = false;
	protected $fillable = ['year', 'event_id', 'user_id', 'rank', 'score', 'details'];

	public function user()
	{
		return $this->belongsTo('User');
	}

	public function event()
	{
		return $this->belongsTo('Event');
	}

	/**
	 * Delete old ranks for a given year (and event) and insert new ranks
	 * that have been previously calculated in the NationalChampionship model.
	 *
	 */
	public static function updateRanks($year, $eventId, $finalRanks)
	{
		self::where('year', $year)->where('event_id', $eventId)->delete();

		foreach ($finalRanks as $userId => $entry) {
			self::create(array(
				'year' => $year,
				'user_id' => $userId,
				'event_id' => $eventId,
				'rank' => $entry['rank'],
				'score' => $entry['score'],
				'details' => $entry['details'],
			));
		}
	}

}