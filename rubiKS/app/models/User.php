<?php

use Zizaco\Confide\ConfideUser;

class User extends ConfideUser {

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
     * Ardent validation rules
     *
     * @var array
     */
    public static $rules = array(
        'email' => 'required|email|unique:users',
        'password' => 'required|min:4|confirmed',
        'password_confirmation' => 'min:4',
    );

    /**
     * Change user password - overwrites ConfideUser's buggy method!
     * https://github.com/Zizaco/confide/pull/229
     *
     * @param  $params
     * @return string
     */
    public function resetPassword( $params )
    {
        //$password = array_get($params, 'password', '');
        $this->password = array_get($params, 'password', '');
        $this->password_confirmation = array_get($params, 'password_confirmation', '');

        $passwordValidators = array(
            'password' => static::$rules['password'],
            'password_confirmation' => static::$rules['password_confirmation'],
        );
        //$validationResult = static::$app['confide.repository']->validate($passwordValidators);
        $validationResult = $this->validate($passwordValidators);

        if ( $validationResult )
        {
            return static::$app['confide.repository']
                //->changePassword( $this, static::$app['hash']->make($password) );
                ->changePassword( $this, static::$app['hash']->make($this->password) );
        }
        else{
            return false;
        }
    }

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