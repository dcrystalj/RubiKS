<div id="achievements" class="panel panel-success">
	<div class="panel-heading"><b>Državno prvenstvo</b></div>
	<div class="panel-body">
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

			@foreach ($finalMedals as $finalMedal)
				@if ($medal->year == $finalMedal->year)
					{{ Help::medal($finalMedal->rank) }} <b>{{ $finalMedal->rank }}. mesto</b> v skupnem seštevku <b>DP {{ $finalMedal->year }}</b> <br>
				@endif
			@endforeach
		@endif
		{{ Help::medal($medal->rank) }} <b>{{ $medal->rank }}. mesto</b> v disciplini <b>{{ $medal->event->name }}</b> na <b>DP {{ $medal->year }}</b> <br>
	@endforeach
	</div>
</div>