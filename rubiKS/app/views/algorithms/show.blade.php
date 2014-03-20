@extends('main')
@section('content')
	<h4>Algoritmi</h4>
	<h3>{{ $competition->name }} <small>{{ Help::date($competition->date) }}</small></h3>
	@if(count($contents) > 0)
		@foreach ($contents as $f)
			<a href="{{ asset($path . '/' . $competition->short_name . '/' . $f) }}">{{ $f }}</a>
		@endforeach
	@else
		Algoritmi za to tekmo niso na voljo.
	@endif
@stop