@extends('main')
@section('content')
	<h4>Tekmovalci</h4>
	<table class="table table-striped table-condensed">
		<thead>
			<tr class="text-left">
				<th></th>
				<th>Tekmovalec</th>
				<th>Država</th>
				<th>Spol</th>
				<th>RubiKS ID</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($users as $user)
				<tr>
					<td class="text-right">{{ $i++ }}.</td>
					<td>
					@if ($user['nationality'] === 'SI')
						<a class="competitor_home" href="{{ url('competitors', $user['club_id']) }}">
					@else
						<a class="competitor_guest" href="{{ url('competitors', $user['club_id']) }}">
					@endif
						{{ $user->getFullName(TRUE) }}</a>
						</td>
					<td>
						{{ Help::country($user['nationality']) }}
					</td>
					<td>{{ $user->gender }}</td>
					<td>{{ $user['club_id'] }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@stop