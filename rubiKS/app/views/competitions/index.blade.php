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
						<td class="competition_year" rowspan="{{ nrCompetitionsInAYear($competitions, $competition->year) }}">
							<div class="rotate">
								<b>{{ $competition->year }}</b>
							</div>
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
@stop