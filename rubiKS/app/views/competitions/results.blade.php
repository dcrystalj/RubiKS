@foreach ($events as $event)
	@foreach ($results[$event->id] as $roundId => $roundResults)
		<table class="table table-condensed table-striped table-bordered">
			<thead>
				<tr class="gray_header">
					<th colspan="5">{{ $event->name }} @unless ($roundId == '0')- {{ $roundId }}. krog @endunless</th>
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
					<?php $competitor = $result->user; ?>
					<tr>
						<td>{{ $result->round_rank }}.</td>
						<td>{{ $competitor->link }}</td>
						<td>
							{{ Result::parse($result->single, $event->readable_id) }}
							@if ($result->isSingleNR()) <b>NR</b> @elseif ($result->isSinglePB()) PB @endif {{-- NR/PB --}}
						</td>
						@if ($event->showAverage())
							<td>
								{{ Result::parse($result->average, $event->readable_id) }}
								@if ($result->isAverageNR()) <b>NR</b> @elseif ($result->isAveragePB()) PB @endif {{-- NR/PB --}}
							</td>
							<td><small>{{ Result::parseAllString($result->results, $event->readable_id); }}</small></td>
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