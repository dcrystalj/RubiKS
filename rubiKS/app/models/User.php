<?php

use Zizaco\Confide\ConfideUser;
use Zizaco\Entrust\HasRole;

class User extends ConfideUser {
	use HasRole;

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
        'name' => 'required|min:2',
        'last_name' => 'required|min:2',
        'gender' => 'required',
        'nationality' => 'required',
        'birth_date' => 'required',
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

    /*
     * Ardent-like hack
     */
    public function updateUnique()
    {
    	$oldRule = self::$rules['email'];
    	self::$rules['email'] .= ',id,' . $this->id;
        $result = $this->save();
        self::$rules['email'] = $oldRule;
        return $result;
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

	public function getRememberToken()
	{
	    return $this->remember_token;
	}

	public function setRememberToken($value)
	{
	    $this->remember_token = $value;
	}

	public function getRememberTokenName()
	{
	    return 'remember_token';
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

	public function registrations()
	{
		return $this->hasMany('Registration');
	}

	public function getGenderAttribute($g)
	{
		//return $g === 'm' ? 'M' : 'Ž';
		return $g === 'm' ? 'moški' : 'ženski';
	}

	public function getRawGenderAttribute()
	{
		return $this->attributes['gender'];
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

	/**
	 * Accessor for compatibility with Administrator.
	 */
	public function getFullNameAttribute($inverted)
	{
		return $this->getFullName($inverted);
	}

	/**
	 * Generate a link to competitor's page.
	 */
	public function getLinkAttribute()
	{
		return '<a href="' . route('competitors.show', $this->attributes['club_id']) . '">' . $this->full_name . '</a>';
	}

	public function getLinkInverseAttribute()
	{
		return '<a href="' . route('competitors.show', $this->attributes['club_id']) . '">' . $this->getFullName(TRUE) . '</a>';
	}

	public function getLinkDistinctForeignAttribute()
	{
		return $this->link . ($this->nationality == 'SI' ? '' : ' *');
	}

	public function getLinkDistinctForeignInverseAttribute()
	{
		return $this->link_inverse . ($this->nationality == 'SI' ? '' : ' *');
	}

    public function isClubMember()
    {
        $year = (int) date("Y");
        if (date("n") == 1) $year--; // date("n") = month (1-12)
        return $year <= $this->membership_year;
    }

	public static function validGender($gender)
	{
		return in_array($gender, ['m', 'f']);
	}

	public static function validNationality($nationality)
	{
		return array_key_exists($nationality, Help::$nationalities);
	}

	/*
	 * Generates club ID.
	 */
	public static function generateClubId($user)
	{
        $search = array(' ', 'č', 'Č', 'š', 'Š', 'ž', 'Ž', 'ć', 'Ć', 'đ', 'Đ');
        $replace = array('', 'C', 'C', 'S', 'S', 'Z', 'Z', 'C', 'C', 'D', 'D');
        $name = str_replace($search, $replace, $user->name);
        $last_name = str_replace($search, $replace, $user->last_name);
        $name = preg_replace('/[^A-Za-z]/', '', $name);
        $last_name = preg_replace('/[^A-Za-z]/', '', $last_name);
		return strtoupper($user->nationality . $user->getRawGenderAttribute() . substr($user->birth_date, 2, 2) . substr($last_name, 0, 3)  . substr($name, 0, 2) . date('y'));
	}

	/*
	 * Get all confirmed users.
	 */
	public static function allConfirmed()
	{
		return User::where('confirmed', '1');
	}

	/**
     * If provided password is empty, use the old one instead.
     * Ensures compatibility with Administrator.
     *
     * @param string $value
     */
    public function setPasswordAttribute($value)
    {
    	if (empty($value) && isset($this->password)) {
    		$this->attributes['password'] = $this->password;
    		$this->attributes['password_confirmation'] = $this->password;
    	} else {
    		$this->attributes['password'] = $value;
    	}
    }

    public function setConfirmedAttribute($value)
    {
        if ($this->attributes['confirmed'] == "0" && $value == "1") {
            $address = $this->email;
            Mail::send('emails.confirmed', array('user' => $this), function($message) use ($address) {
                $message->to($address)->subject('Vaš račun je potrjen!');
            });
            $this->attributes['confirmed'] = "1";
        } else {
            $this->attributes['confirmed'] = $value;
        }
    }

    public function getMedals()
    {
    	$rubiksCube = Event::whereReadableId('333');
    	$rubiksCube = $rubiksCube->id;

    	return array(
    		NationalChampionshipStatsFinal::where('user_id', $this->id)
    			->where('year', '<', date('Y'))
    			->orderBy('year', 'desc')
    			->orderBy('rank')
    			->get(),
    		NationalChampionshipStatsEvent::where('user_id', $this->id)
    			->where('year', '<', date('Y'))
    			//->where('rank', '<=', 3)
    			->where(function($query) use ($rubiksCube)
    			{
    				$query->where('rank', '<=', '3')->orWhere('event_id', $rubiksCube);
    			})
    			->orderBy('year', 'desc')
    			->orderBy('rank')
    			->get()
    			->sort(function ($a, $b) use ($rubiksCube) // Sort by: year DESC, 333 first, rank asc
    			{
    				if ($a->year == $b->year) {
    					if ($a->event_id == $rubiksCube) {
	    					return -1;
	    				} elseif ($b->event_id == $rubiksCube) {
	    					return 1;
	    				} else {
	    					return $a->rank > $b->rank;
	    				}
    				} else {
    					return $a->year < $b->year;
    				}
    			})
    	);
    }

    public function getStats()
    {
    	$medals = array();
    	$dnf = (string) Result::dnfNumericalValue();
    	$dns = (string) Result::dnsNumericalValue();

    	$medal_1 = Result::where('user_id', $this->id)->where('medal', '1')->count();
    	$medal_2 = Result::where('user_id', $this->id)->where('medal', '2')->count();
    	$medal_3 = Result::where('user_id', $this->id)->where('medal', '3')->count();
    	$medal_sum = $medal_1 + $medal_2 + $medal_3;

    	$rc = Event::whereReadableId('333');
    	$medal_rc_1 = Result::where('user_id', $this->id)->where('event_id', $rc->id)->where('medal', '1')->count();
    	$medal_rc_2 = Result::where('user_id', $this->id)->where('event_id', $rc->id)->where('medal', '2')->count();
    	$medal_rc_3 = Result::where('user_id', $this->id)->where('event_id', $rc->id)->where('medal', '3')->count();
    	$medal_rc_sum = $medal_rc_1 + $medal_rc_2 + $medal_rc_3;

    	// http://stackoverflow.com/questions/12344795/count-the-number-of-occurences-of-a-string-in-a-varchar-field
    	$results = Result::select(DB::raw("SUM(ROUND( CHAR_LENGTH(results) - CHAR_LENGTH( REPLACE(results, '@', '') ) ) + 1) as 'sum_results'"))
    		->where('user_id', $this->id)
    		->first();
    	$results = $results === null ? 0 : $results->sum_results;

    	$dns_results = Result::select(DB::raw("SUM(ROUND(( CHAR_LENGTH(results) - CHAR_LENGTH( REPLACE(results, '" . $dns . "', '') ) ) / " . strlen($dns) . ") ) as 'sum_dns'"))
    		->where('user_id', $this->id)
    		->first();
    	$dns_results = $dns_results === null ? 0 : $dns_results->sum_dns;

    	$dnf_results = Result::select(DB::raw("SUM(ROUND(( CHAR_LENGTH(results) - CHAR_LENGTH( REPLACE(results, '" . $dnf . "', '') ) ) / " . strlen($dnf) . ") ) as 'sum_dnf'"))
    		->where('user_id', $this->id)
    		->first();
    	$dnf_results = $dnf_results === null ? 0 : $dnf_results->sum_dnf;

    	$last_competition = Result::where('user_id', $this->id)->orderBy('date', 'desc')->first();
    	$last_competition = $last_competition === null ? '0000-00-00' : $last_competition->date;

    	return array(
    		'medals' => array('1' => $medal_1, '2' => $medal_2, '3' => $medal_3, 'sum' => $medal_sum),
    		'medals_rc' => array('1' => $medal_rc_1, '2' => $medal_rc_2, '3' => $medal_rc_3, 'sum' => $medal_rc_sum),
    		'single_nr' => Result::where('user_id', $this->id)->where('single_nr', 1)->count(),
    		'single_pb' => Result::where('user_id', $this->id)->where('single_pb', 1)->count(),
    		'average_nr' => Result::where('user_id', $this->id)->where('average_nr', 1)->count(),
    		'average_pb' => Result::where('user_id', $this->id)->where('average_pb', 1)->count(),
    		'competitions' => Result::select('competition_id')->distinct()->where('user_id', $this->id)->orderBy('date', 'desc')->with('competition')->get(),
    		'delegation' => Competition::where('delegate1', $this->id)->orWhere('delegate2', $this->id)->orWhere('delegate3', $this->id)->orderBy('date', 'desc')->get(),
    		'event_registrations' => Result::select(array('competition_id', 'event_id'))->where('user_id', $this->id)->count(),
    		'results' => $results - $dns_results,
    		'dnf_results' => $dnf_results,
    		'dns_results' => $dns_results,
    		'last_competition' => $last_competition,
    	);
    }

}
