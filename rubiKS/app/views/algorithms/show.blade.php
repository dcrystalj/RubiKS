@extends('main')
@section('content')
	<h4>Algoritmi</h4>
	<h3>{{ $competition->name }} <small>{{ $competition->getParsedDate() }}</small></h3>
	@unless (count($contents) > 0)
		Algoritmi za to tekmo niso na voljo.
	@endunless
    <ul>
	@foreach ($contents as $file)
		<li><a href="{{ asset($path . '/' . $competition->short_name . '/' . $file) }}">{{ $file }}</a></li>
	@endforeach
    </ul>
@stop
