@extends('main')
@section('content')
	<h4>Rezultati po disciplinah</h4>
	@include('rankings.list')
	<h3>{{ $event->name }}, @if ($resultType == "average") povprečje @else posamezno @endif</h3>
	<table class="table table-condensed table-striped">
		<thead>
			<tr>
				<th>Rang</th>
				<th>Tekmovalec</th>
				<th>@if ($resultType == "average") Povprečje @else Čas @endif</th>
				@if ($event->showAverage()) <th>Posamezni časi</th> @endif
				<th>Datum</th>
				<th>Tekmovanje</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($results as $result)
				<tr>
					<td>{{ $result->injectedRank }}.</td>
					<td><a href="{{ route('competitors.show', $result->user->club_id) }}">{{ $result->user->getFullName() }}</a></td>
					<td><b>{{ Result::parse($result[$resultType], $event->readable_id) }}</b></td>
					@if ($event->showAverage()) <td><small>{{ Result::parseAllString($result->results, $event->readable_id) }}</small></td> @endif
					<td><small>{{ Date::parse($result->date) }}</small></td>
					<td><small><a title="{{ $result->competition->name }}" href="{{ route('competitions.show', $result->competition->short_name) }}">{{ $result->competition->short_name }}</a></small></td>
				</tr>
			@endforeach
		</tbody>
	</table>
@stop