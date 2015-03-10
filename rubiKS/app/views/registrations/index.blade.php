@extends('main')
@section('content')
	<h4>Prijave na tekmovanja</h4>

	<?php $registered = []; ?>
	@if ($registrations->count() > 0)
		<table class="table table-condensed">
			<thead>
				<tr>
					<th>Tekmovanje</th>
					<th>Datum</th>
					{{--<th>Discipline</th>--}}
					<th>Prijava potrjena</th>
				</tr>
			</thead>
			@foreach ($registrations as $registration)
				<?php $competition = $registration->competition; $registered[] = $competition->short_name; ?>
				<tr>
					<td><a href="{{ route('registrations.show', $competition->short_name) }}">{{ $competition->name }}</a></td>
					<td>{{ Date::parse($competition->date) }}</td>
					{{--<td>{{ $registration->events }}</td>--}}
					<td>@if ($registration->isConfirmed()) da @else ne @endif</td>
				</tr>
			@endforeach
		</table>
	@else
		Trenutno nimate odprtih prijav.
	@endif

	<hr>

	<h4>Prihajajoča tekmovanja</h4>
	<table class="table table-condensed">
		<thead>
			<th>Tekmovanje</th>
			<th>Datum</th>
		</thead>
		@foreach ($competitions as $competition)
		<?php if (in_array($competition->short_name, $registered)) continue; ?>
		<tr>
			<td><a href="{{ route('registrations.show', $competition->short_name) }}">{{ $competition->name }}</a></td>
			<td>{{ Date::parse($competition->date) }}</td>
		</tr>
		@endforeach
	</table>

	@if (count($competitions) < 1)
		<p><b>Trenutno ni najavljenih tekem.</b></p>
		<p>
			Za plan tekem obiščite <a href="http://www.rubik.si/forum/"><b>forum</b></a>. <br>
			Za organizacijo tekem se obrnite na <a href="http://www.rubik.si/delegates"><b>delegate</b></a>.
		</p>
	@endif
	<br><br>
	@include('alerts')
@stop
