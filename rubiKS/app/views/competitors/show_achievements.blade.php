{{-- Medals: http://findicons.com/search/medal - licensed under "Commercial-use" and "No Link Required" --}}
<div id="achievements" class="panel panel-success">
	<div class="panel-heading"><b>Državno prvenstvo</b></div>
	<div class="panel-body">
	@foreach ($finalMedals as $medal)
		<b>{{ Help::medal($medal->rank) }} {{ $medal->rank }}. mesto</b> v skupnem seštevku <b>DP {{ $medal->year }}</b> <br>
	@endforeach
	@if (count($finalMedals) > 0 && count($eventMedals) > 0) <hr> @endif
	@foreach ($eventMedals as $medal)
		<b>{{ Help::medal($medal->rank) }} {{ $medal->rank }}. mesto</b> v disciplini <b>{{ $medal->event->name }}</b> na <b>DP {{ $medal->year }}</b> <br>
	@endforeach
	</div>
</div>