@extends('main')
@section('content')
	<h4>Vaša prijava za {{ $competition->name }}</h4>
	<form method="POST" action="{{{ URL::to('registrations', $competition->short_name)  }}}" accept-charset="UTF-8" class="form-horizontal">
		<input name="_method" type="hidden" value="PUT">
    	<input type="hidden" name="_token" value="{{{ Session::getToken() }}}">
    	<fieldset>
			@include('registrations.form')

			<div class="form-actions form-group">
	            <div class="col-sm-offset-2 col-sm-10">
	                <button type="submit" class="btn btn-primary">Uredi prijavnico</button>
	            </div>
	        </div>
		</fieldset>
	</form>

	<div class="pull-right">
		<a href="{{ route('registrations.index') }}"><button type="submit" class="btn btn-link">Nazaj na prijave</button></a>
	</div>
@stop