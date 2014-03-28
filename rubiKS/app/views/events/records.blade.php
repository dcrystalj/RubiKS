@extends('main')
@section('content')
	<h4>Rekordi</h4>
	<table class="table table-condensed">
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
			<th colspan="6">{{ $event->name }}</th>
		</tr>
		@if ($event->singleRecord != NULL)
		<tr>
			<td><small>Posamezno</small></td>
			<td><b><a href="{{ url('competitors', $event->singleRecord->user->club_id) }}">{{ $event->singleRecord->user->getFullName() }}</a></b></td>
			<td>{{ Result::parse($event->singleRecord->single, $event->readable_id) }}</td>
			<td><a href="{{ url('competitions', $event->singleRecord->competition->short_name) }}" title="{{ Date::parse($event->singleRecord->competition->date) }}">{{ $event->singleRecord->competition->short_name }}</a></td>
			<td></td>
		</tr>
		@else 
		<tr>
			<td colspan="5">Ta disciplina še ni bila izvedena.</td>
		</tr>
		@endif

		@if ($event->showAverage() AND $event->averageRecord != NULL)
		<tr>
			<td><small>Povprečje</small></td>
			<td><b><a href="{{ url('competitors', $event->singleRecord->user->club_id) }}">{{ $event->averageRecord->user->getFullName() }}</a></b></td>
			<td>{{ Result::parse($event->averageRecord->average, $event->readable_id) }}</td>
			<td><a href="{{ url('competitions', $event->averageRecord->competition->short_name) }}" title="{{ Date::parse($event->averageRecord->competition->date) }}">{{ $event->averageRecord->competition->short_name }}</a></td>
			<td><small>{{ Result::parseAllString($event->averageRecord->results, $event->readable_id) }}</small></td>
		</tr>
		@endif
	@endforeach
		</tbody>
	</table>
@stop