<div class="panel panel-default">
	<div class="panel-heading"><b>Najboljši rezultati</b></div>

	<table class="table table-condensed">
		<thead>
			<tr>
				<th> &nbsp; </th>
				<th>Disciplina</th>
				<th>Posamezno</th>
				<th>Povprečje</th>
			</tr>
		</thead>
		<tbody>
			<?php $i = 1; ?>
			@foreach ($results as $e => $a)
			<?php $event = $events[$e]; ?>
			<tr id="e{{ $event->readable_id }}" class="_clickable @if($i++ % 2) results_odd @endif" >
				<td></td>
				<td>{{ $event->name }}</td>
				<td><span title="Tekma">{{ Result::parse($a['single']->single, $event->readable_id) }}</span></td>
				@if ($event->showAverage())
				<td><span title="Tekma">{{ Result::parse($a['average']->average, $event->readable_id) }}</span></td>
				@else
				<td>/</td>
				@endif
			</tr>
			<tr>
				<td colspan="4" class="text-center">
					<div id="chart_e{{ $event->readable_id }}" class="_chart" @unless($event->readable_id === '333') style="display:none;" @endif>
						<div id="chart_{{ $event->readable_id }}" style="width:100%;"></div>
					</div>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
{{-- HighCharts --}}
<script src="{{ asset('assets/highcharts/highcharts.js') }}"></script>
<script src="{{ asset('assets/highcharts/charts.js') }}"></script>
<script>
	$('._clickable').click(function() { $('#chart_' + this.id).slideToggle('fast'); loadChart(this.id.substr(1)); });
	$('._chart').click(function() { $(this).slideToggle('fast'); });
	loadChart("333");
</script>