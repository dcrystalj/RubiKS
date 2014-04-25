<h1>{{ Lang::get('confide::confide.email.account_confirmation.subject') }}</h1>

<p>{{ Lang::get('confide::confide.email.account_confirmation.greetings', array( 'name' => $user->full_name )) }}!</p>

<p>{{ Lang::get('confide::confide.email.account_confirmation.body') }}</p>
<a href='{{{ URL::to("user/confirm/{$user->confirmation_code}") }}}'>
    {{{ URL::to("user/confirm/{$user->confirmation_code}") }}}
</a>

<p>{{ Lang::get('confide::confide.email.account_confirmation.farewell') }}, <br> Rubik klub Slovenija</p>
