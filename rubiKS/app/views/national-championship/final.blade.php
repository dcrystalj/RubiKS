@extends('main')
@section('content')
	<h4>Skupni vrstni red državnega prvenstva {{{ $year }}}</h4>

	@include('national-championship.selection')

	<table class="table table-condensed table-striped">
		<thead>
			<tr>
				<th class="text-right">Mesto</th>
				<th>Tekmovalec</th>
				<th class="text-right">Točke</th>
			</tr>
		</thead>
		<tbody>
			{{-- 2011 --}}
			@if ($year == 2011) 
				<tr>
					<td colspan="3" class="text-center">
						<b>Leta 2011 nismo beležili skupnega vrstnega reda. </b>
					</td>
				</tr>
			@endif

			@foreach ($stats as $entry)
				<?php $user = $entry->user; ?>
				<tr>
					<td class="text-right">{{ $entry->rank }}.</td>
					<td>{{ $user->link_distinct_foreign }}</td>
					<td class="text-right">
						<?php 
							// Format competitor's ranks
							$individualRanks = array();
							foreach (explode(',', $entry->details) as $rank) {
								if (!array_key_exists($rank, $individualRanks)) $individualRanks[$rank] = 0;
								$individualRanks[$rank] += 1;
							}
							array_walk($individualRanks, function(&$item, $rank) { $item = $item . 'x ' . $rank . '. mesto'; });
						?>
						<span title="{{ implode(', ', $individualRanks) }}">
							{{ Help::formatChampionshipPoints($entry->score) }}
						</span>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@stop