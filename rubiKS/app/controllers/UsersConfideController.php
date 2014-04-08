<?php
/*
|--------------------------------------------------------------------------
| Confide Controller Template
|--------------------------------------------------------------------------
|
| This is the default Confide controller template for controlling user
| authentication. Feel free to change to your needs.
|
*/

class UsersConfideController extends BaseController {

    /**
     * 
     */
    public function __construct()
    {
        $this->beforeFilter(function($routes, $request)
        {
            if (!Auth::guest()) return Redirect::to('/');
            return $this->registrationsOpenedFilter($routes, $request);
        }, 
        ['only' => ['getCreate', 'postIndex'] ]);
    }

    /**
     * Displays the form for account creation
     *
     */
    public function getCreate($competitionShortName)
    {
        $competition = Competition::getCompetitionByShortName($competitionShortName);
        $events = Competition::getEvents($competition->events);
        return View::make(Config::get('confide::signup_form'))->with('competition', $competition)->with('events', $events);
    }

    /**
     * Stores new account
     *
     */
    public function postIndex()
    {
        $err = FALSE;

        // Check birth date
        if (($birthDate = Date::ymdToDate(Input::get('birth_year'), Input::get('birth_month'), Input::get('birth_day'))) === FALSE) $err = 'Neveljaven datum rojstva.';

        // Check nationality
        if (!User::validNationality(Input::get('nationality'))) $err = 'Neveljavno državljanstvo.';

        // Check gender
        if (!User::validGender(Input::get('gender'))) $err = 'Neveljaven spol.';

        // Display errors
        if ($err !== FALSE) {
            return Redirect::to('user/create/' . Input::get('competition'))
                        ->withInput(Input::except('password'))
                        ->with('error', $err);
        }

        // Competition
        $competition = Competition::getCompetitionByShortName(Input::get('competition'));
        // Parse events
        $competitionEvents = Competition::getEvents($competition->events, TRUE);
        $events = [];
        foreach ($competitionEvents as $event) {
            if (Input::get('event_' . $event) == '1') $events[] = $event;
        }
        $events = implode(' ', $events);

        // User
        $user = new User;

        // Email and password are checked in ConfideUser->save()
        $user->email = Input::get('email');
        $user->password = Input::get('password');

        $user->name = Input::get('name');
        $user->last_name = Input::get('last_name');

        $user->gender = Input::get('gender');
        $user->nationality = Input::get('nationality');
        $user->city = Input::get('city');
        $user->forum_nickname = Input::get('forum_nickname');
        $user->birth_date = $birthDate;
        $user->joined_date = date('Y-m-d');

        $user->club_id = User::generateClubId($user);


        /* Preveri, da je club_id res unikaten! */


        // The password confirmation will be removed from model
        // before saving. This field will be used in Ardent's
        // auto validation.
        $user->password_confirmation = Input::get('password_confirmation');

        // Save if valid. Password field will be hashed before save
        $user->save();

        if ($user->id) {
            // Save registration
            $registration = new Registration;
            $registration->competition_id = $competition->id;
            $registration->user_id = $user->id;
            $registration->events = $events;
            $registration->notes = Input::get('notes');

            if (!$registration->save()) {
                return Redirect::to('user/create/' . Input::get('competition'))->withInput(Input::except('password'))->with('error', 'Napačna prijava.');
            }

            //$notice = Lang::get('confide::confide.alerts.account_created') . ' ' . Lang::get('confide::confide.alerts.instructions_sent'); 
            $notice = 'Vaš račun je bil uspešno ustvarjen. Po potrditvi boste dobili e-mail z dodatnimi navodili.';
                    
            // Redirect with success message, You may replace "Lang::get(..." for your custom message.
            return Redirect::to('user/login')->with('notice', $notice);
        } else {
            // Get validation errors (see Ardent package)
            $error = $user->errors()->all(':message');

            return Redirect::to('user/create/' . Input::get('competition'))->withInput(Input::except('password'))->with('error', $error);
        }
    }

    /**
     * Displays the login form
     *
     */
    public function getLogin()
    {
        if (Confide::user()) {
            // If user is logged, redirect to internal 
            // page, change it to '/admin', '/dashboard' or something
            return Redirect::to('/');
        } else {
            return View::make(Config::get('confide::login_form'));
        }
    }

    /**
     * Attempt to do login
     *
     */
    public function postLogin()
    {
        $input = array(
            'email'    => Input::get('email'), // May be the username too
            //'username' => Input::get('email'), // so we have to pass both
            'club_id' => Input::get('email'),
            'password' => Input::get('password'),
            'remember' => Input::get('remember'),
        );

        // If you wish to only allow login from confirmed users, call logAttempt
        // with the second parameter as true.
        // logAttempt will check if the 'email' perhaps is the username.
        // Get the value from the config file instead of changing the controller
        if (Confide::logAttempt($input, Config::get('confide::signup_confirm')))  {
            // Redirect the user to the URL they were trying to access before
            // caught by the authentication filter IE Redirect::guest('user/login').
            // Otherwise fallback to '/'
            // Fix pull #145
            return Redirect::intended('/'); // change it to '/admin', '/dashboard' or something
        } else {
            $user = new User;

            // Check if there was too many login attempts
            if (Confide::isThrottled($input)) {
                $err_msg = Lang::get('confide::confide.alerts.too_many_attempts');
            } elseif($user->checkUserExists($input) and !$user->isConfirmed($input)) {
                $err_msg = Lang::get('confide::confide.alerts.not_confirmed');
            } else {
                $err_msg = Lang::get('confide::confide.alerts.wrong_credentials');
            }

            return Redirect::to('user/login')->withInput(Input::except('password'))->with( 'error', $err_msg );
        }
    }

    /**
     * Attempt to confirm account with code
     *
     * @param  string  $code
     */
    public function getConfirm( $code )
    {
        if (Confide::confirm($code)) {
            $notice_msg = Lang::get('confide::confide.alerts.confirmation');
            return Redirect::to('user/login')->with( 'notice', $notice_msg );
        } else {
            $error_msg = Lang::get('confide::confide.alerts.wrong_confirmation');
            return Redirect::to('user/login')->with( 'error', $error_msg );
        }
    }

    /**
     * Displays the forgot password form
     *
     */
    public function getForgot()
    {
        return View::make(Config::get('confide::forgot_password_form'));
    }

    /**
     * Attempt to send change password link to the given email
     *
     */
    public function postForgot()
    {
        if (Confide::forgotPassword(Input::get('email'))) {
            $notice_msg = Lang::get('confide::confide.alerts.password_forgot');
            return Redirect::to('user/login')->with( 'notice', $notice_msg );
        } else {
            $error_msg = Lang::get('confide::confide.alerts.wrong_password_forgot');
            return Redirect::to('user/forgot')->withInput()->with( 'error', $error_msg );
        }
    }

    /**
     * Shows the change password form with the given token
     *
     */
    public function getReset( $token )
    {
        return View::make(Config::get('confide::reset_password_form'))->with('token', $token);
    }

    /**
     * Attempt change password of the user
     *
     */
    public function postReset()
    {
        $input = array(
            'token'=>Input::get( 'token' ),
            'password'=>Input::get( 'password' ),
            'password_confirmation'=>Input::get( 'password_confirmation' ),
        );

        // By passing an array with the token, password and confirmation
        if (Confide::resetPassword($input)) {
            $notice_msg = Lang::get('confide::confide.alerts.password_reset');
            return Redirect::to('user/login')->with( 'notice', $notice_msg );
        } else {
            $error_msg = Lang::get('confide::confide.alerts.wrong_password_reset');
            return Redirect::to('user/reset/'.$input['token'])->withInput()->with( 'error', $error_msg );
        }
    }

    /**
     * Log the user out of the application.
     *
     */
    public function getLogout()
    {
        Confide::logout();
        
        return Redirect::to('/');
    }

    /**
     *  
     */
    public function registrationsOpenedFilter($routes, $request)
    {
        if (count($routes->parameters()) > 0) {
            $shortName = $routes->parameters()['one'];
        } elseif (Input::has('competition')) {
            $shortName = Input::get('competition');
        } else {
            App::abort(404);
        }

        if (!Competition::getCompetitionByShortName($shortName)->registrationsOpened()) App::abort(404);
    }

}
