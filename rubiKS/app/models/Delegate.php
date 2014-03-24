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

	public static function getUserData($delegates)
	{
		$ids = array();
		$nrDelegating = array();
		foreach ($delegates as $delegate) {
			$delegate->nr_delegating = $delegate->nrDelegating();
			$ids[] = $delegate->user_id;
		}

		$_competitors = User::find($ids);
		$competitors = array();
		foreach ($_competitors as $competitor) {
			$competitors[$competitor->id] = $competitor;
		}

		return $competitors;
	}

}