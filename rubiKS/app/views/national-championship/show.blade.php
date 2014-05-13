@extends('main')
@section('content')
	<h4>Državno prvenstvo {{{ $year }}}</h4>

	@include('national-championship.selection')
	
	@include('national-championship.showranks')
	<hr>
	@include('national-championship.showfinal')

@stop