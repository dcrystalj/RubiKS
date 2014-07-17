@extends('main')
@section('content')
	<h4>Delegati</h4>
	<table class="table table-condensed">
		<thead>
			<tr>
				<th>Ime in priimek</th>
				<th>Stopnja</th>
				<th>Št. delegiranj</th>
				<th>Regija</th>
				<th>Kontakt</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($delegates as $delegate)
				<?php if (!$delegate->isActive()) continue; ?>
				<tr>
					<td>{{ $delegate->user->link }}</td>
					<td>{{ $delegate->degree }}</td>
					<td>{{ $delegate->nr_delegating }}</td>
					<td>{{ $delegate->region }}</td>
					<td><a href="mailto:{{ HTML::email($delegate->contact) }}"><span class="glyphicon glyphicon-envelope"></span></a></td>
				</tr>
			@endforeach
		</tbody>
	</table>

	<dl class="dl-horizontal">
		<dt>A</dt>
		<dd>v kolikor ne tekmuje lahko vodi tekmo samostojno, sicer ob pomoči drugega delegata ali kandidata</dd>
		<dt>B</dt>
		<dd>tekmo lahko vodi ob pomoči delegata stopnje A</dd>
		<dt>kandidat - K</dt>
		<dd>na usposabljanju za delegata</dd>
		<!--
		<dt>neaktiven - N</dt>
		<dd>v zadnjih sezonah brez delegiranj</dd>
		-->
	</dl>
@stop