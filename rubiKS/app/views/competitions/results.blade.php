@foreach ($events as $event)
	@foreach ($results[$event->id] as $roundId => $roundResults)
		<?php $round = $rounds[$roundId]; ?>
		<table class="table table-condensed table-striped table-bordered table-results">
			<thead>
				<tr class="gray_header">
					<th colspan="5">
						{{ $event->name }}
						@unless ($round->id == Round::DEFAULT_ROUND_ID) - {{ $round->name }} @endunless
					</th>
				</tr>
				<tr>
					<th style="width: 4% !important;">#</th>
					<th style="width: 26% !important;">Tekmovalec</th>
					@if ($event->showAverage())
					<th style="width: 15% !important;">Posamezno</th>
					<th style="width: 15% !important;">Povprečje</th>
					<th style="width: 40% !important;">Vsi poskusi</th>
					@elseif ($event->attempts > 1)
					<th style="width: 15% !important;">Posamezno</th>
					<th style="width: 55% !important;">Vsi poskusi</th>
					@else
					<th style="width: 70% !important;">Posamezno</th>
					@endif
				</tr>
			</thead>
			<tbody>
				@foreach ($roundResults as $result)
					<?php $competitor = $result->user; ?>
					<tr>
						<td>{{ $result->round_rank }}.</td>
						<td>
							{{ $competitor->link }}
							@if ( (count($results[$event->id]) <= 1 || $roundId == Round::DEFAULT_FINAL_ROUND_ID) && $result->medal > 0 )
								{{ Help::medal($result->medal) }}
							@endif
						</td>
						<td>
							{{ Result::parse($result->single, $event->readable_id) }}
							@if ($result->isSingleNR() && $result->user->nationality === "SI")
								 <b>NR</b>
							@elseif ($result->isSingleNR())
								 <b>NB</b>
							@elseif ($result->isSinglePB())
							 	 PB
							@endif {{-- NR/NB/PB --}}
						</td>
						@if ($event->showAverage())
							<td>
								{{ Result::parse($result->average, $event->readable_id) }}
								@if ($result->isAverageNR() && $result->user->nationality === "SI")
									 <b>NR</b>
								@elseif ($result->isAverageNR())
									 <b>NB</b>
								@elseif ($result->isAveragePB())
									 PB
								@endif {{-- NR/NB/PB --}}
							</td>
							<td><small>{{ Result::parseAllString($result->results, $event->readable_id); }}</small></td>
						@elseif ($event->attempts > 1)
							<td><small>{{ Result::parseAllString($result->results, $event->readable_id); }}</small></td>
						@endif
					</tr>
				@endforeach
			</tbody>
		</table>
	@endforeach
@endforeach
