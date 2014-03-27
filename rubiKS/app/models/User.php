<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	public $timestamps = true;
	protected $softDelete = false;

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}

	public function results()
	{
		return $this->hasMany('Result');
	}

	public function getGenderAttribute($g) {
		//return $g === 'm' ? 'M' : 'Ž';
		return $g === 'm' ? 'moški' : 'ženski';
	}

	public function getParsedJoinedDate()
	{
		return Date::parse($this->attributes['joined_date']);
	}

	public function getFullName($inverted = FALSE)
	{
		if ($inverted) {
			$fullName = $this->attributes['last_name'] . ' ' . $this->attributes['name'];
		} else {
			$fullName = $this->attributes['name'] . ' ' . $this->attributes['last_name'];
		}

		return mb_convert_case($fullName, MB_CASE_TITLE);
	}

}