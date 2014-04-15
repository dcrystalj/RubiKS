@extends('main')
@section('content')
	<h4>Vaša prijava za {{ $competition->name }}</h4>
	Discipline: <br>
	{{ $registration->events }}
	<br><br>

	Discipline, ki se bodo izvajale na tekmi: <br>
	{{ $competition->events }}
	<br><br>
	Opombe: <p>{{{ $registration->notes }}}</p>
	<hr>

	<a href="{{ route('registrations.edit', $competition->short_name) }}"><button class="btn btn-primary">Uredi prijavo</button></a>

	{{ Form::open(array('route' => array('registrations.destroy', $competition->short_name), 'method' => 'delete', 'class' => 'inline')) }}
    	<button type="submit" class="btn btn-danger">Izbriši prijavo</button>
	{{ Form::close() }}

	<span class="pull-right">
		<a href="{{ route('registrations.index') }}"><button class="btn btn-link">Nazaj na prijave</button></a>
	</span>

	<br><br>
	@include('alerts')
@stop