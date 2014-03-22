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
				@foreach ($roundResults as $result)
					<?php $competitor = $competitors[$result->user_id]; ?>
					<tr>
						<td>{{ $result->round_rank }}.</td>
						<td><a href="{{ url('competitors', $competitor->club_id) }}">{{ $competitor->name . ' ' . $competitor->last_name }}</a></td>
						<td>
							{{ Result::parse($result->single, $event->readable_id) }}
							@if ($result->isSingleNR()) <b>NR</b> @else @if ($result->isSinglePB()) PB @endif @endif {{-- NR/PB --}}
						</td>
						@if ($event->showAverage())
							<td>
								{{ Result::parse($result->average, $event->readable_id) }}
								@if ($result->isAverageNR()) <b>NR</b> @else @if ($result->isAveragePB()) PB @endif @endif {{-- NR/PB --}}
							</td>
							<td>
							<small>
								<?php $resultAllResults = Result::parseAll($result->results); ?>
								@foreach ($resultAllResults as $i => $subResult)
									@if ($subResult['exclude'])
										[{{ $subResult['t'] }}]@if ($i + 1 < count($resultAllResults)), @endif
									@else
										{{ $subResult['t'] }}@if ($i + 1 < count($resultAllResults)), @endif
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