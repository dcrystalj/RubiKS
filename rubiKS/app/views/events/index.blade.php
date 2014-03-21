@extends('main')
@section('content')
	<h4>Discipline</h4>
	<table class="table table-condensed">
		<thead>
			<tr>
				<th>Naziv discipline</th>
				<th>Kratica</th>
				<th>Št. poskusov</th>
				<th>Časovna omejitev</th>
			</tr>
		</thead>
		<tbody>
		@foreach ($events as $event)
			<tr>
				<td><a href="{{ url('events', $event->readable_id) }}">{{ $event->name }}</a></td>
				<td>{{ $event->short_name }}</td>
				<td>{{ $event->attempts }}</td>
				<td>{{ $event->time_limit }}</td>
			</tr>
		@endforeach
		</tbody>
	</table>
@stop