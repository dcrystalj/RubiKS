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
					<td>{{ $user->link_distinct_foreign_inverse }}</td>
					<td>
						{{ Help::country($user['nationality']) }}
					</td>
					<td>{{ $user->gender }}</td>
					<td>{{ $user->club_id }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@stop