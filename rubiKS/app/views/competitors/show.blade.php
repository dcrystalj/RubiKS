@extends('main')
@section('content')
	<!-- <h4>Tekmovalci</h4> -->
	<h3>{{ $user->getFullName() }}</h3>

	<div class="competitors_block_left">
		<table class="table table-condensed">
			<tr>
				<td>RubiKS ID</td>
				<td>{{ $user->club_id }}</td>
			</tr>
			{{--<tr>
				<td>Ime in priimek</td>
				<td>{{ $user->getFullName() }}</td>
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
		<img class="competitor_image img-thumbnail" alt="{{ $user->getFullName() }}" src="{{ asset("files/photos/{$user['club_id']}.jpg") }}" width="150" height="200">
	</div>

	<ul class="nav nav-pills">
		<li class="active"><a href="#best" data-toggle="tab">Najboljši rezultati</a></li>
		<li><a href="#achievements" data-toggle="tab">Državno prvenstvo</a></li>
		<li><a href="#stats" data-toggle="tab">Statistika</a></li>
	</ul>
	<br>
	<div class="tab-content">
		<div class="tab-pane fade in active" id="best">
			@include('competitors.show_results')
		</div>
		<div class="tab-pane fade" id="achievements">
			@include('competitors.show_achievements')
		</div>
		<div class="tab-pane fade" id="stats">
			@include('competitors.show_stats')
		</div>
	</div>
@stop
