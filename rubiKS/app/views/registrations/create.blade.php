@extends('main')
@section('content')
	<h4>Prijava na tekmovanje</h4>
	<form method="POST" action="{{{ URL::to('registrations')  }}}" accept-charset="UTF-8" class="form-horizontal">
    	<input type="hidden" name="_token" value="{{{ Session::getToken() }}}">
    	<fieldset>
			@include('registrations.form')

			<div class="form-actions form-group">
	            <div class="col-sm-offset-2 col-sm-10">
	                <button type="submit" class="btn btn-primary">Pošljite prijavnico</button>
	            </div>
	        </div>
		</fieldset>
	</form>
@stop