@extends('main')
@section('content')
	<h4>Tekme</h4>
	<h2>{{ $competition->name }}</h2>
	<table class="table table-condensed">
		<tr>
			<td>Datum prireditve</td>
			<td>{{ Date::parse($competition->date) }}</td>
		</tr>
		<tr>
			<td>Čas trajanja</td>
			<td>{{ $competition->time }}</td>
		</tr>
		<tr>
			<td>Država</td>
			<td>{{ $competition->country }}</td>
		</tr>
		<tr>
			<td>Kraj</td>
			<td>{{ $competition->city }}</td>
		</tr>
		<tr>
			<td>Prizorišče</td>
			<td>{{ $competition->venue }}</td>
		</tr>
		<tr>
			<td>Opis</td>
			<td>{{ $competition->description }}</td>
		</tr>
		<tr>
			<td>Organizator</td>
			<td>{{ $competition->organiser }}</td>
		</tr>
		<tr>
			<td>1. delegat</td>
			<td>@if ($d1 !== NULL) <a href="{{ url('competitors', $d1->club_id) }}">{{ $d1->name . ' ' . $d1->last_name }}</a> @endif</td>
		</tr>
		<tr>
			<td>2. delegat</td>
			<td>@if ($d2 !== NULL) <a href="{{ url('competitors', $d2->club_id) }}">{{ $d2->name . ' ' . $d2->last_name }}</a> @endif</td>
		</tr>
		<tr>
			<td>Pomožni delegat</td>
			<td>@if ($d3 !== NULL) <a href="{{ url('competitors', $d3->club_id) }}">{{ $d3->name . ' ' . $d3->last_name }}</a> @endif</td>
		</tr>
		<tr>
			<td>Omejitev št. tekmovalcev</td>
			<td>{{ $competition->max_competitors }}</td>
		</tr>
		<tr>
			<td>Startnina</td>
			<td>{{ $competition->registration_fee }}</td>
		</tr>
		<tr>
			<td>Zaporedna RubiKS tekma</td>
			<td>{{ '/' }}</td>
		</tr>
		<tr>
			<td>ID tekme</td>
			<td>{{ $competition->short_name }}</td>
		</tr>
		<tr>
			<td>Discipline</td>
			<td>{{ $competition->events }}</td>
		</tr>
	</table>
@stop