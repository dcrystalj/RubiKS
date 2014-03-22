@extends('main')
@section('content')
	<h4>Algoritmi</h4>
	<h3>{{ $competition->name }} <small>{{ $competition->getParsedDate() }}</small></h3>
	@unless (count($contents) > 0)
		Algoritmi za to tekmo niso na voljo.
	@endunless
	@foreach ($contents as $file)
		<a href="{{ asset($path . '/' . $competition->short_name . '/' . $file) }}">{{ $file }}</a>
	@endforeach
@stop