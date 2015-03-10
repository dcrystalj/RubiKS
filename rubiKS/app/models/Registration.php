<?php

class Registration extends Eloquent {

	protected $table = 'registrations';
	public $timestamps = true;
	protected $softDelete = false;

	public static function boot()
	{
		parent::boot();

		Registration::created(function($registration)
		{
			$registration->sendMailToDelegates();
		});
	}

	public function user()
	{
		return $this->belongsTo('User');
	}

	public function competition()
	{
		return $this->belongsTo('Competition');
	}

	public function isConfirmed()
	{
		return $this->confirmed == '1';
	}

	public function signedUpForEvent($event)
	{
		$events = Competition::getEvents($this->events, TRUE);
		return in_array($event, $events);
	}

	public static function getRegistration($userId, $competitionId)
	{
		return Registration::where('user_id', $userId)->where('competition_id', $competitionId)->first();
	}

	public static function parseSelectedEvents($competition, $data) {
		$competitionEvents = Competition::getEvents($competition->events, TRUE);
        $events = [];
        foreach ($competitionEvents as $event) {
            if (array_key_exists('event_' . $event, $data) AND $data['event_' . $event] == '1') $events[] = $event;
        }
        return implode(' ', $events);
	}

	public function sendMailToDelegates()
	{
		$user = Auth::user();
		$competition = $this->competition;
		$competitionShortName = $competition->short_name;
		$emails = array();
		if ($competition->delegate1 != NULL) $emails[] = User::find($competition->delegate1)->email;
		if ($competition->delegate2 != NULL) $emails[] = User::find($competition->delegate2)->email;
		if ($competition->delegate3 != NULL) $emails[] = User::find($competition->delegate3)->email;
		Mail::send(
			'emails.registration_notify_delegate',
			array('competition' => $competition, 'name' => $user->full_name),
			function($message) use ($emails, $competitionShortName) {
				$message->to($emails)->subject('Nova prijava na ' . $competitionShortName);
			}
		);
	}

}
