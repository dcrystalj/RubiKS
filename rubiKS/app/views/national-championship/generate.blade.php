@extends('main')
@section('content')
	<table class="table table-condensed table-bordered">
		@foreach ($statuses as $status)
			<tr><td>{{ $status[0] }}</td><td>{{ $status[1] }}</td><td>{{ $status[2] ? 'success' : '<b>fail</b>' }}</td></tr>
		@endforeach

		<tr><td colspan="3"><b>Done!</b></td></tr>
	</table>
@stop