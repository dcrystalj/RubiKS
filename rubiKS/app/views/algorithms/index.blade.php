@extends('main')
@section('content')
	<h4>Arhiv algoritmov</h4>
	@foreach ($contents as $f)
		<a href="{{ route('algorithms.show', $f) }}">{{ $f }}</a> <br>
	@endforeach
@stop
