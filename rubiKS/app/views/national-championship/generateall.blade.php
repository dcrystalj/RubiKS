@extends('main')
@section('content')
	@for ($i = NationalChampionshipPeriod::minYear(); $i <= date('Y'); $i++)
		<a href="{{ url('national-championship/generate-all', $i) }}">{{ $i }}</a> <br>
	@endfor
	<br>
	<p>OPOMBA: Za leto 2011 se skupni vrstni red državnega prvenstva NE zgenerira, saj ga še nismo beležili. Funkcija zato vrne False.</p>
@stop