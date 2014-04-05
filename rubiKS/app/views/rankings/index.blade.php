@extends('main')
@section('content')
	<h4>Rezultati po disciplinah</h4>
	@include('rankings.list')
	<br>

	<h4>Rezultati po tekmah</h4>
	<a href="{{ url('competitions/finished') }}">Rezultati po tekmah</a>
	<br><br>

	<h4>Rezultati po tekmovalcih</h4>
	<a href="{{ url('competitors') }}">Rezultati po tekmovalcih</a>
@stop