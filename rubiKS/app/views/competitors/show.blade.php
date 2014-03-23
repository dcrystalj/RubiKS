@extends('main')
@section('content')
	<!-- <h4>Tekmovalci</h4> -->
	<h3>{{ $user->name }} {{ $user->last_name }}</h3>
	<div class="competitors_block_left">
		<table class="table table-condensed">
			<tr>
				<td>RubiKS ID</td>
				<td>{{ $user->club_id }}</td>
			</tr>
			{{--<tr>
				<td>Ime in priimek</td>
				<td>{{ $user->name }} {{ $user->last_name }}</td>
			</tr>--}}
			<tr>
				<td>Vzdevek</td>
				<td>{{ $user->forum_nickname }}</td>
			</tr>
			<tr>
				<td>Spol</td>
				<td>{{ $user->gender }}</td>
			</tr>
			<tr>
				<td>Država</td>
				<td>{{ Help::country($user->nationality) }}</td>
			</tr>
			<tr>
				<td>Mesto</td>
				<td>{{ $user->city }}</td>
			</tr>
			<tr>
				<td>Datum registracije</td>
				<td>{{ $user->getParsedJoinedDate() }}</td>
			</tr>
			<tr>
				<td>Status</td>
				<td>{{ $user->club_authority }}</td>
			</tr>
		</table>
	</div>
	<div class="competitors_block_right pull-right">
		<img class="competitor_image img-thumbnail" alt="{{ $user->name }} {{ $user->last_name }}" src="http://www.rubik.si/klub/foto/{{ $user['club_id'] }}.jpg" width="150" height="200">
	</div>
	<table class="table table-condensed">
		<thead>
			<tr>
				<th>Disciplina</th>
				<th>Posamezno</th>
				<th>Povprečje</th>
			</tr>
		</thead>
		<tbody>
			<?php $i = 1; ?>
			@foreach ($results as $e => $a)
			<?php $event = $events[$e]; ?>
			<tr id="e{{ $event->readable_id }}" class="_clickable @if($i++ % 2) results_odd @endif" >
				<td>{{ $event->name }}</td>
				<td><span title="Tekma">{{ Result::parse($a['single']->single, $event->readable_id) }}</span></td>
				@if ($event->showAverage())
				<td><span title="Tekma">{{ Result::parse($a['average']->average, $event->readable_id) }}</span></td>
				@else
				<td>/</td>
				@endif
			</tr>
			<tr>
				<td colspan="3">
					<span id="chart_e{{ $event->readable_id }}" class="_chart" @unless($event->readable_id === '333') style="display:none;" @endif>
						<img src="http://www.rubik.si/klub/plotuser.php?id={{ $user->club_id }}&iddisc={{ $event->readable_id }}">
					</span>
					{{-- Zakaj span tagi? http://stackoverflow.com/questions/7192335/jquery-slide-toggle-not-working-properly --}}
				</td>
			</tr>
			@endforeach
		</tbody>
		<script>
			$('._clickable').click(function() { $('#chart_' + this.id).slideToggle('fast'); });
			$('._chart').click(function() { $(this).slideToggle('fast'); });
		</script>
	</table>
@stop