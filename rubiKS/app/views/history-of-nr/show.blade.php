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

	@foreach ($allResults as $type => $results)
	<?php if (count($results) < 1) continue; ?>

	<table class="table table-condensed table-striped">
		<thead>
			<tr>
				<th>Datum</th>
				<th>@if ($type == 'single') Posamezno @else Povprečje @endif</th>
				<th>Tekmovalec</th>
				<th>Tekmovanje</th>
			</tr>
		</thead>

		@foreach ($results as $result)
			<?php if ($result->user->nationality != $country) continue; ?>
			<tr>
				<td>
					<small>{{ Date::parse($result->date) }}</small>
				</td>
				<td>
					<b>
						@if ($type == 'single')
							{{ Result::parse($result->single, $event->readable_id) }}
						@else
							{{ Result::parse($result->average, $event->readable_id) }}
						 @endif
					</b>
				</td>
				<td>{{ $result->user->link }}</td>
				<td><a href="{{ route('competitions.show', $result->competition->short_name) }}">{{ $result->competition->name }}</a></td>
			</tr>
		@endforeach		

	</table>
	
	@endforeach

@stop