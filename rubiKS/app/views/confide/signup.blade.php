@extends('main')
@section('content')
<div class="alert alert-warning">
    Podatke vnašajte natančno, kot so navedeni na osebnem dokumentu! <br> Podatki označeni z * so obvezni.
</div>
<form method="POST" action="{{{ (Confide::checkAction('UserController@store')) ?: URL::to('user')  }}}" accept-charset="UTF-8" class="form-horizontal">
    <input type="hidden" name="_token" value="{{{ Session::getToken() }}}">
    <fieldset>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="name">Ime *</label>
            <div class="col-sm-10">
                <input class="form-control" placeholder="Ime" type="text" name="name" id="name" value="{{{ Input::old('name') }}}">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="last_name">Priimek *</label>
            <div class="col-sm-10">
                <input class="form-control" placeholder="Priimek" type="text" name="last_name" id="last_name" value="{{{ Input::old('last_name') }}}">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="gender">Spol *</label>
            <div class="col-sm-10">
                <select name="gender" id="gender" class="form-control">
                    <option value="m" @if (Input::old('gender') == 'm') selected @endif>moški</option>
                    <option value="f" @if (Input::old('gender') == 'f') selected @endif>ženski</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="nationality">Državljanstvo *</label>
            <div class="col-sm-10">
                <select name="nationality" id="nationality" class="form-control">
                    @foreach (Help::$nationalities as $code => $nationality)
                        <option value="{{ $code }}" @if ($code == Input::old('nationality')) selected @endif>{{ $nationality }} ({{ Help::country($code) }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="birth_date">Datum rojstva *</label>
            <div class="col-sm-10 form-inline">
                <select name="birth_day" id="birth_day" class="form-control">
                    <option value="" disabled selected>Dan</option>
                    @for ($i = 1; $i <= 31; $i++)
                        <option value="{{ $i }}" @if ($i == Input::old('birth_day')) selected @endif>{{ $i }}</option>
                    @endfor
                </select>

                <select name="birth_month" id="birth_month" class="form-control">
                    <option value="" disabled selected>Mesec</option>
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" @if ($i == Input::old('birth_month')) selected @endif>{{ $i }}</option>
                    @endfor
                </select>

                <select name="birth_year" id="birth_year" class="form-control">
                    <option value="" disabled selected>Leto</option>
                    @for ($i = date('Y'); $i >= 1900; $i--)
                        <option value="{{ $i }}" @if ($i == Input::old('birth_year')) selected @endif>{{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="email">E-pošta <small></small> *</label>
            <div class="col-sm-10">
                <input class="form-control" placeholder="E-pošta" type="text" name="email" id="email" value="{{{ Input::old('email') }}}">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="password">Geslo *</label>
            <div class="col-sm-10">
                <input class="form-control" placeholder="Geslo" type="password" name="password" id="password">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="password_confirmation">Ponovite geslo *</label>
            <div class="col-sm-10">
                <input class="form-control" placeholder="Ponovite geslo" type="password" name="password_confirmation" id="password_confirmation">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="city">Kraj bivanja</label>
            <div class="col-sm-10">
                <input class="form-control" placeholder="Kraj bivanja" type="text" name="city" id="city" value="{{{ Input::old('city') }}}">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="forum_nickname">Vzdevek na forumu</label>
            <div class="col-sm-10">
                <input class="form-control" placeholder="Vzdevek na forumu" type="text" name="forum_nickname" id="forum_nickname" value="{{{ Input::old('forum_nickname') }}}">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="user_notes">Opombe</label>
            <div class="col-sm-10">
                <input class="form-control" placeholder="Opombe" type="text" name="user_notes" id="user_notes" value="{{{ Input::old('user_notes') }}}">
            </div>
        </div>

        <hr>

        @include('registrations.form')

        @include('alerts')

        <div class="form-actions form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary">Pošljite prijavnico</button>
            </div>
        </div>

    </fieldset>
</form>

@stop
