@extends('main')
@section('content')
	<h4>Tekmovanja</h4>
	<table class="table table-striped table-condensed">
		<thead>
			<tr class="text-left">
				<th></th>
				<th>Tekma</th>
				<th>Mesto</th>
				<th>Datum</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($competitions as $i => $competition)
				<tr>
					<td class="text-right">{{ count($competitions) - $i }}.</td>
					<td><a href="{{ url('competitions', $competition->short_name) }}">{{ $competition->name }}</a></td>
					<td>{{ $competition->city }}</td>
					<td>{{ $competition->getParsedDate() }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@stop