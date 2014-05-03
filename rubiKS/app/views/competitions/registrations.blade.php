<table class="table table-condensed table-striped table-bordered print-page-break">
	<thead>
		<tr class="gray_header">
			<th colspan="{{ count($events) + 3 }}">Seznam prijavljenih</th>
		</tr>
		<tr class="gray_header">
			<th><small>#</small></th>
			<th><small>Tekmovalec</small></th>
			@foreach ($events as $event)
				<th><small>{{ $event->short_name }}</small></th>
			@endforeach
			<th>∑</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($registrations as $i => $registration)
			<?php $competitor = $registration->user; $userEvents = 0; ?>
			<tr>
				<td class="text-right"><small>{{ $i + 1 }}.</small></td>
				<td><small><a href="{{ route('competitors.show', $competitor->club_id) }}">{{ $competitor->getFullName() }}</a></small></td>
				@foreach ($events as $event)
					<td><small>@if ($registration->signedUpForEvent($event->readable_id)) X <?php $userEvents++; ?>@else - @endif</small></td>
				@endforeach
				<td><small>{{ $userEvents }}</small></td>
			</tr>
		@endforeach
	</tbody>
</table>
@if ($competition->registrationsOpened())
	<center>
		@if (Auth::guest())
			<a href="{{ url('user/create', $competition->short_name) }}"><button type="button" class="btn btn-default"><b>Če še nimate RubiKS računa se lahko prijavite tukaj.</b></button></a>
		@else
			<a href="{{ route('registrations.show', $competition->short_name) }}"><button type="button" class="btn btn-default"><b>Prijavite se na tekmo.</b></button></a>
		@endif
	</center>
@else
	<center><b>Prijave so zaprte.</b></center>
@endif