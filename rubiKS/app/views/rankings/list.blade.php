<form id="eventForm" class="form">
	<label for="event">Izberi disciplino: </label>
	<select name="event" id="event">
		@foreach ($events as $e)
			<option @if (isset($event) AND $e->readable_id == $event->readable_id) selected @endif value="{{ $e->readable_id }}">{{ $e->name }}</option>
		@endforeach
	</select>
	<button id="single" type="button" class="btn btn-default">Posamezno</button>
	<button id="average" type="button" class="btn btn-default">Povprečje</button>
</form>
<script>
	$('#single, #average').click(function() { window.location = '{{ route('rankings.index') }}' + '/' + $('#event')[0].value + '/?type=' + this.id; });
</script>