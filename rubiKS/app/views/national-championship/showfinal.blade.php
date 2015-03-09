<h4>Skupni vrstni red - {{{ $year }}}, {{ $event->name }}</h4>

<table class="table table-condensed table-striped table-results">
	<thead>
		<tr>
			<th class="text-right"></th>
			<th>Tekmovalec</th>
			<th>Izid sezone</th>
			<th>Uvrstitve</th>
			<th class="text-right">Točke</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($finalEventStats as $entry)
			<?php
				$user = $entry->user;
				list($entry->seasonBest, $entry->periods) = explode('|', $entry->details);
			?>

			<tr>
				<td class="text-right">@if ((int) $year <= 2011 || ($entry->score != "" && $entry->score > 0)) {{ $entry->rank }}. @endif</td>
				<td>{{ $user->link_distinct_foreign }}</td>
				<td>{{ Result::parse($entry->seasonBest, $event->readable_id) }}</td>
				<td>{{ implode(' / ', explode(',', $entry->periods)) }}</td>
				<td class="text-right">@if ($entry->score != "") {{ Help::formatChampionshipPoints($entry->score) }} @endif</td>
			</tr>
		@endforeach
	</tbody>
</table>
