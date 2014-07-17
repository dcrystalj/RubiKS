<div class="text-center">
	<form id="eventForm" class="form">
		<label for="year">Leto: </label>
		<select name="year" id="year">
			@for ($y = (int) date('Y'); $y >= NationalChampionshipPeriod::minYear(); $y--)
				<option @if ($year == $y) selected @endif value="{{ $y }}">{{ $y }}</option>
			@endfor
		</select>
		 &nbsp; 
		<label for="event">Disciplina: </label>
		<select name="event" id="event">
			<option value=""></option>
			@foreach ($events as $e)
				<option @if (isset($event) AND $e->readable_id == $event->readable_id) selected @endif value="{{ $e->readable_id }}">{{ $e->name }}</option>
			@endforeach
		</select>
		 &nbsp; 
		<a href="{{ url('national-championship/final/' . $year) }}"><button type="button" class="btn btn-default btn-sm">Skupni vrstni red</button></a>
	</form>
	<br>
</div>
<script>
	$('#event, #year').on("change", function() { 
		var year = $('#year')[0].value,
			event = $('#event')[0].value;

		if (event == '') {
			window.location = '{{ url('national-championship/final') }}' + '/' + year;
		} else {
			window.location = '{{ url('national-championship/event') }}' + '/' + year + '/' + event;
		}
	});
</script>