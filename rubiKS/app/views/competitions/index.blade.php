@extends('main')
@section('content')
	<?php
		function nrCompetitionsInAYear($competitions, $year)
		{
			$i = 0;
			foreach ($competitions as $competition) if ($competition->year == $year) $i++;
			return $i;

		}
	?>
	<h4>Tekmovanja</h4>
	<table class="table table-striped table-condensed">
		<thead>
			<tr class="text-left">
				<th></th>
				<th></th>
				<th>Tekma</th>
				<th>Mesto</th>
				<th>Datum</th>
			</tr>
		</thead>
		<tbody>
			<?php $years = array(); ?>
			@foreach ($competitions as $i => $competition)
				<tr>
					@if (!in_array($competition->year, $years))
						<?php $nrComp = nrCompetitionsInAYear($competitions, $competition->year); ?>
						<td class="competition_year" rowspan="{{ $nrComp }}">
							@if ($nrComp > 1)
							<div class="rotate">
								<b>{{ $competition->year }}</b>
							</div>
							@else
							 <div>
							 	<b>{{ $competition->year }}</b>
							 </div>
							@endif
						</td>
						<?php $years[] = $competition->year; ?>
					@endif
					<td class="text-right"><small>{{ count($competitions) - $i }}.</small></td>
					<td><a href="{{ route('competitions.show', $competition->short_name) }}">{{ $competition->name }}</a></td>
					<td>{{ $competition->city }}</td>
					<td>{{ $competition->getParsedDate() }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
	@if (isset($future) && count($competitions) < 1)
		<p><b>Trenutno ni najavljenih tekem.</b></p>
		<p>
			Za plan tekem obiščite <a href="http://www.rubik.si/forum/"><b>forum</b></a>. <br>
			Za organizacijo tekem se obrnite na <a href="http://www.rubik.si/delegates"><b>delegate</b></a>.
		</p>
	@endif
@stop
