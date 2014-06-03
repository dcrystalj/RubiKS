@extends('main')
@section('content')
	<h4>Zgodovina državnih rekordov - {{ $event->name }}</h4>

	<br>
	<form id="eventForm" class="form">
		<label for="event">Izberi disciplino: </label>
		<select name="event" id="event">
			@foreach ($events as $e)
				<option @if (isset($event) AND $e->readable_id == $event->readable_id) selected @endif value="{{ $e->readable_id }}">{{ $e->name }}</option>
			@endforeach
		</select>
	</form>
	<br>
	<script>
		$('#event').on('change', (function() { window.location = '{{ url('history-of-nr') }}' + '/event/' + $('#event')[0].value; }));
	</script>

	<table class="table table-condensed table-striped">
		<thead>
			<tr>
				<th>Datum</th>
				<th>Posamezno</th>
				<th>Povprečje</th>
				<th>Tekmovalec</th>
				<th>Tekmovanje</th>
			</tr>
		</thead>

		@foreach ($allResults as $type => $results)
			@foreach ($results as $result)
				<?php if ($result->user->nationality != $country) continue; ?>
				<tr>
					<td>
						<small>{{ Date::parse($result->date) }}</small>
					</td>
					@if ($type == 'single')
						<td>
							<b>{{ Result::parse($result->single, $event->readable_id) }}</b>
						</td>
						<td></td>
					@else
						<td></td>
						<td>
							<b>{{ Result::parse($result->average, $event->readable_id) }}</b>
						</td>
					@endif
					<td>{{ $result->user->link }}</td>
					<td><a href="{{ route('competitions.show', $result->competition->short_name) }}">{{ $result->competition->name }}</a></td>
				</tr>
			@endforeach			
		@endforeach

	</table>
@stop