<div class="panel panel-info">
	<div class="panel-heading"><b>Statistika</b></div>
	<div class="panel-body">
		<table class="table">
			<thead>
				<tr>
					<th colspan="2">Medalje dosežene na tekmovanjih</th>
				</tr>
			</thead>
			<tr>
				<td>Zlate medalje {{ Help::medal(1) }}</td>
				<td><b>{{ $stats['medals']['1'] }}</b> ({{ $stats['medals_rc']['1'] }})</td>
			</tr>
			<tr>
				<td>Srebrne medalje {{ Help::medal(2) }}</td>
				<td><b>{{ $stats['medals']['2'] }}</b> ({{ $stats['medals_rc']['2'] }})</td>
			</tr>
			<tr>
				<td>Bronaste medalje {{ Help::medal(3) }}</td>
				<td><b>{{ $stats['medals']['3'] }}</b> ({{ $stats['medals_rc']['3'] }})</td>
			</tr>
			<tr>
				<td>Skupaj medalje {{ Help::medal(1) }} + {{ Help::medal(2) }} + {{ Help::medal(3) }}</td>
				<td><b>{{ $stats['medals']['sum'] }}</b> ({{ $stats['medals_rc']['sum'] }})</td>
			</tr>
			<tr>
				<td style="border-top: 0px;" colspan="2"><small>V oklepaju so medalje dosežene v disciplini Rubikova kocka.</small></td>
			</tr>
		</table>

		<table class="table">
			<thead>
				<tr>
					<th colspan="2">Rekordi</th>
				</tr>
			</thead>
			<tr>
				<td>Državni rekordi (posamično + povprečje)</td>
				<td>{{ $stats['single_nr'] }} + {{ $stats['average_nr'] }} = <b>{{ $stats['single_nr'] + $stats['average_nr'] }}</b></td>
			</tr>
			<tr>
				<td>Osebni rekordi (posamično + povprečje)</td>
				<td>{{ $stats['single_pb'] }} + {{ $stats['average_pb'] }} = <b>{{ $stats['single_pb'] + $stats['average_pb'] }}</b></td>
			</tr>
		</table>

		<table class="table">
			<thead>
				<tr>
					<th colspan="2">Tekmovanja</th>
				</tr>
			</thead>
			<tr>
				<td>Udeležbe na tekmovanjih <br>
					<span id="stats_competitions" style="display:none;">
						@foreach ($stats['competitions'] as $entry)
							<?php $competition = $entry->competition; ?>
							<a href="{{ route('competitions.show', $competition->short_name) }}">{{ $competition->name }}</a> <br>
						@endforeach
					</span>
				</td>
				<td><b><a id="click_competitions" href="#">{{ count($stats['competitions']) }}</a></b></td>
			</tr>
			@if (count($stats['delegation']) > 0)
			<tr>
				<td>Delegiranja <br>
					<span id="stats_delegation" style="display:none;">
						@foreach($stats['delegation'] as $competition)
							<a href="{{ route('competitions.show', $competition->short_name) }}">{{ $competition->name }}</a> <br>
						@endforeach
					</span>
				</td>
				<td><b><a href="#" id="click_delegation">{{ count($stats['delegation']) }}</a></b></td>
			</tr>
			@endif
			<tr>
				<td>Število vseh prijav na discipline</td>
				<td><b>{{ $stats['event_registrations'] }}</b></td>
			</tr>
			<tr>
				<td>Število izmerjenih poskusov</td>
				<td><b>{{ $stats['results'] }}</b></td>
			</tr>
			<tr>
				<td>Število neveljavnih poskusov</td>
				<td><b>{{ $stats['dnf_results'] }}</b></td>
			</tr>
			<tr>
				<td>Zanesljivost tekmovalca (poskusi brez DNF)</td>
				@if ($stats['results'] > 0)
				<td><b>{{ number_format(($stats['results'] - $stats['dnf_results']) / $stats['results'] * 100, 2) }} %</b></td>
				@else
				<td></td>
				@endif
			</tr>
			<tr>
				<td>Pretečen čas od zadnjega tekmovanja</td>
				<td><span title="{{ Date::parse($stats['last_competition']) }}"><b>{{ round((strtotime(date('Y-m-d')) - strtotime($stats['last_competition'])) / 3600 / 24) }} dni </b></span></td>
			</tr>
		</table>
		<script>
			$('#click_competitions').click(function(e) { e.preventDefault(); $('#stats_competitions').slideToggle(); })
			$('#click_delegation').click(function(e) { e.preventDefault(); $('#stats_delegation').slideToggle(); })
		</script>
	</div>
</div>
