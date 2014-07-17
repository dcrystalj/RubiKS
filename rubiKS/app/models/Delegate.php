<?php

class Delegate extends Eloquent {

	protected $table = 'delegates';
	public $timestamps = false;
	protected $softDelete = false;

	public function user()
	{
		return $this->belongsTo('User');
	}

	public function delegating()
	{
		return DB::select("SELECT COUNT(id) as 'nr_delegating' FROM `" . with(new Competition)->getTable() . "` WHERE delegate1 = " . $this->user_id . " OR delegate2 = " . $this->user_id . " OR delegate3 = " . $this->user_id . "");
	}

	public function nrDelegating()
	{
		$result = $this->delegating();
		return $result[0]->nr_delegating;
	}

	public function isActive()
	{
		return $this->activity == '1';
	}

	public static function injectAdditionalDataAndSort($delegates)
	{
		foreach ($delegates as $delegate) $delegate->nr_delegating = $delegate->nrDelegating();
		$delegates = $delegates->sortBy(function ($delegate) {
			return -1 * $delegate->nr_delegating;
		});
		return $delegates;
	}

}