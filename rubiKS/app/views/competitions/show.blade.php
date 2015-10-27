@extends('main')
@section('content')
	<h4>Tekme</h4>
	<h2>{{ $competition->name }}</h2>
	<table class="table table-condensed">
		<tr>
			<td>Datum prireditve</td>
			<td>{{ $competition->getParsedDate() }}</td>
		</tr>
		<tr>
			<td>Čas trajanja</td>
			<td>{{ $competition->time }}</td>
		</tr>
		<tr>
			<td>Kraj</td>
			<td>{{ $competition->city }}, {{ $competition->country }}</td>
		</tr>
		<tr>
			<td>Prizorišče</td>
			<td colspan="3">{{ $competition->venue }}</td>
		</tr>
		<tr>
			<td>Opis</td>
			<td colspan="3">{{ $competition->description }}</td>
		</tr>
		<tr>
			<td>Organizator</td>
			<td>{{ $competition->organiser }}</td>
		</tr>
		<tr>
			<td>Delegati</td>
			<td>
				@if ($delegate1 !== NULL) {{ $delegate1->link }} <small>(1. delegat)</small>@endif{{ $delegate2 !== NULL ? ", " : "" }}
				@if ($delegate2 !== NULL) {{ $delegate2->link }} <small>(2. delegat)</small>@endif{{ $delegate3 !== NULL ? ", " : "" }}
				@if ($delegate3 !== NULL) {{ $delegate3->link }} <small>(pomožni delegat)</small>@endif
			</td>
		</tr>
		<tr>
			<td>Omejitev št. tekmovalcev</td>
			<td>{{ $competition->max_competitors }}</td>
		</tr>
		@if ($competition->registration_fee != "")
		<tr>
			<td>Startnina</td>
			<td>{{ $competition->registration_fee }}</td>
		</tr>
		@else
		<tr>
			<td>Pogoji sodelovanja</td>
			<td><a href="{{ asset('files/sodelujmo.pdf') }}">Preberi!</a></td>
		</tr>
		@endif
		<tr>
			<td>Zaporedna RubiKS tekma</td>
			<td>{{ Competition::where('date', '<', $competition->date)->count() + 1 }}.</td>
		</tr>
		<tr>
			<td>ID tekme</td>
			<td>{{ $competition->short_name }}</td>
		</tr>
		<tr>
			<td>Discipline</td>
			<td colspan="3">
				@foreach ($events as $i => $event)
					<a href="{{ route('events.show', $event->readable_id) }}" title="{{ $event->name }}">{{ $event->short_name }}</a>@if ($i + 1!= count($events)), @endif
				@endforeach
			</td>
		</tr>
		@if ($competition->isFinished())
		<tr>
			<td>Mešalni algoritmi</td>
			<td><a href="{{ route('algorithms.show', $competition->short_name) }}">arhiv</a></td>
		</tr>
		@endif
	</table>

	@if ($competition->isFinished())
		@include('competitions.results')
	@elseif ($competition->registrationsOpened() OR $competition->registrationsClosed())
		@include('competitions.registrations')
	@endif
@stop
