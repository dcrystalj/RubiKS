@extends('main')
@section('content')
	<h4>Rekordi</h4>
	<table class="table table-condensed table-results">
		<thead>
			<tr>
				<th>Disciplina</th>
				<th>Tekmovalec</th>
				<th>Čas</th>
				<th>Tekmovanje</th>
				<th>Podrobnosti</th>
			</tr>
		</thead>
		<tbody>
		@foreach ($events as $event)
			<tr class="gray_header">
				<td colspan="6">{{ $event->name }}</td>
			</tr>

			@if ($event->singleRecord->count() == 0)
			<tr>
				<td colspan="5">Ta disciplina še ni bila izvedena.</td>
			</tr>
			@endif

			@foreach ($event->singleRecord as $i => $single)
			<tr>
				<td>@if ($i == 0) <small>Posamezno</small> @endif</td>
	            <td><b>{{ $single->user->link }}</b></td>
	            <td>{{ Result::parse($single->single, $event->readable_id) }}</td>
	            <td><a href="{{ route('competitions.show', $single->competition->short_name) }}" title="{{ Date::parse($single->competition->date) }}">{{ $single->competition->short_name }}</a></td>
	            <td></td>
			</tr>
	        @endforeach

			@foreach ($event->averageRecord as $i => $average)
			<tr>
				<td><small>Povprečje</small></td>
				<td><b>{{ $average->user->link }}</b></td>
				<td>{{ Result::parse($average->average, $event->readable_id) }}</td>
				<td><a href="{{ route('competitions.show', $average->competition->short_name) }}" title="{{ Date::parse($average->competition->date) }}">{{ $average->competition->short_name }}</a></td>
				<td><small>{{ Result::parseAllString($average->results, $event->readable_id) }}</small></td>
			</tr>
			@endforeach

		@endforeach
		</tbody>
	</table>
@stop
