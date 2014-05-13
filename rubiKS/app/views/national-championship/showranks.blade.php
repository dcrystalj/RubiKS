<div class="row">
@foreach ($periods as $i => $period)
	<?php if (strtotime($period['start_date']) > strtotime(date('Y-m-d'))) continue; ?>
	<div class="col-md-6">
		<table class="table table-condensed table-striped">
			<thead>
				<tr>
					<th></th>
					<th colspan="2">{{ $i + 1 }}. tekmovalno obdobje ({{ Date::parse($period['start_date']) . ' - ' . Date::parse($period['end_date']) }})</th>
				</tr>
			</thead>
			<tbody>
				@if (count($results[$i]) == 0) <tr><td colspan="333" class="text-center">Za to obdobje ni veljavnih rezultatov.</td></tr> @endif
				@foreach ($results[$i] as $result)
					<?php $user = $result->user; ?>
					<tr>
						<td class="text-right">{{ $result->championship_rank }}. </td>
						<td>{{ $user->link_distinct_foreign }}</td>
						<td>{{ Result::parse($result->$resultType, $event->readable_id) }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>

	@if ($i % 2) </div><div class="row"> @endif
@endforeach
</div>