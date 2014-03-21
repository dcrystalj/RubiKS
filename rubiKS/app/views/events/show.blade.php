@extends('main')
@section('content')
	<h3>{{ $event->name }}</h3>
	<table class="table-condensed">
		<tr>
			<td>Kratica:</td>
			<td>{{ $event->short_name }}</td>
		</tr>
		<tr>
			<td>Število poskusov:</td>
			<td>{{ $event->attempts }}</td>
		</tr>
		<tr>
			<td>Časovna omejitev:</td>
			<td>{{ $event->time_limit }}</td>
		</tr>
		<tr>
			<td>Kaj merimo:</td>
			<td>{{ $event->type }}</td>
		</tr>
		{{--<tr>
			<td>Vodenje rekordov:</td>
			<td>Kje obstaja ta podatek?</td>
		</tr>--}}
		<tr>
			<td>Državni rekord:</td>
			<td>
				@if ($event->show_average)
					{{ Result::parse($average->average, $event->readable_id) }} ({{ Result::parse($single->single, $event->readable_id) }})
				@else
					{{ Result::parse($single->single, $event->readable_id) }}
				@endif
			</td>
		</tr>
		<tr>
			<td>Pomoč:</td>
			<td><a href="{{ $event->help }}">{{ $event->help }}</a></td>
		</tr>
	</table>
	<br>
	{{ $event->description }}
@stop