{{-- Medals: http://findicons.com/search/medal - licensed under "Commercial-use" and "No Link Required" --}}
<div id="achievements" class="panel panel-success">
	<div class="panel-heading"><b>Državno prvenstvo</b></div>
	<div class="panel-body">
	@foreach ($finalMedals as $medal)
		<b>{{ Help::medal($medal->rank) }} {{ $medal->rank }}. mesto</b> v skupnem seštevku <b>DP {{ $medal->year }}</b> <br>
	@endforeach

	@if (count($finalMedals) > 0 && count($eventMedals) > 0) <hr> @endif

	<?php
		$years = array();
		$first = True;
	?>
	@foreach ($eventMedals as $medal)
		@if (!in_array($medal->year, $years))
			<?php
				$years[] = $medal->year;
				$newYear = True;
				if (!$first) { echo '<br>'; } else { $first = False; }
				?>
			<b>{{ $medal->year }}</b> <br>
		@endif
		<b>{{ Help::medal($medal->rank) }} {{ $medal->rank }}. mesto</b> v disciplini <b>{{ $medal->event->name }}</b> na <b>DP {{ $medal->year }}</b> <br>
	@endforeach
	</div>
</div>