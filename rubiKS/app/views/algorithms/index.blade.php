@extends('main')
@section('content')
	<h4>Arhiv algoritmov</h4>
	@foreach ($contents as $f)
		<a href="{{ url('algorithms', $f) }}">{{ $f }}</a> <br>
	@endforeach
@stop