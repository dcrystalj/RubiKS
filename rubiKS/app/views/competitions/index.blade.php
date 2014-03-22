@extends('main')
@section('content')
	<h4>Tekmovanja</h4>
	<table class="table table-striped table-condensed">
		<thead>
			<tr style="text-align: left;">
				<th></th>
				<th>Tekma</th>
				<th>Mesto</th>
				<th>Datum</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($competitions as $competition)
				<tr>
					<td style="text-align: right;">{{ $i-- }}.</td>
					<td><a href="{{ url('competitions', $competition->short_name) }}">{{ $competition->name }}</a></td>
					<td>{{ $competition->city }}</td>
					<td>{{ $competition->getParsedDate() }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@stop