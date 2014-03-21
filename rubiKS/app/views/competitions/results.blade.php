@foreach ($events as $event)
	@foreach ($results[$event->id] as $roundId => $roundResults)
		<table class="table table-condensed table-striped table-bordered">
			<thead>
				<tr>
					<th colspan="5" style="background-color: #ddd;">{{ $event->name }} @unless ($roundId == '0')- {{ $roundId }}. krog @endunless</th>
				</tr>
				<tr>
					<th>#</th>
					<th>Tekmovalec</th>
					<th>Posamezno</th>
					<th>Povprečje</th>
					<th>Vsi poskusi</th>
				</tr>
			</thead>
			<tbody>
				<?php $rank = 1; ?>
				@foreach ($roundResults as $j => $r)
							<?php $competitor = $competitors[$r->user_id]; ?>
							<tr>
								<td>{{ $rank++ }}.</td>
								<td><a href="{{ url('competitors', $competitor->club_id) }}">{{ $competitor->name . ' ' . $competitor->last_name }}</a></td>
								<td>{{ Result::parse($r->single, $event->readable_id) }}</td>
								@if ($event->show_average === '1')
									<td> {{ Result::parse($r->average, $event->readable_id) }}</td>
									<td>
									<small>
										<?php $res = Result::parseAll($r->results); ?>
									@foreach ($res as $i => $r)
										@if ($r['exclude'])
											[{{ $r['t'] }}]@if ($i + 1 < count($res)), @endif
										@else
											{{ $r['t'] }}@if ($i + 1 < count($res)), @endif
										@endif
									@endforeach

									</small>
									</td>
								@else
									<td>/</td>
									<td>/</td>
								@endif
							</tr>
				@endforeach
			</tbody>
		</table>
	@endforeach
@endforeach