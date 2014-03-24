@extends('main')
@section('content')
	<h4>Člani kluba</h4>
	<table class="table table-condensed">
		<thead>
			<tr>
				<th></th>
				<th>Ime in priimek</th>
				<th>Organ kluba</th>
				<th>Članarina</th>
			</tr>
		</thead>
		<tbody>
		@foreach ($members as $i => $member)
			<tr>
				<td>{{ $i + 1 }}.</td>
				<td>{{ $member->name . ' ' . $member->last_name }}</td>
				<td>{{ $member->club_authority }}</td>
				<td>{{ $member->membership_year }}</td>
			</tr>
		@endforeach
		</tbody>
	</table>

	<p>Ustanovni člani (09. 10. 2010):</p>
	<p>Tjaš Cvek*, Miroslav Delinac, Liljana Delinac, Damijan Duh, Jure Gregorc*, Aljoša Grgurič*, Leon Jarabek*, <nobr>Uroš Jedlovčnik</nobr>, Branko Kobal, Žiga Lesar, Jernej Omulec, Matic Omulec*, Miha Rajter*, Samo Remec*, <nobr>Dominik Sedonja*</nobr>, Damir Tement, Tomaž Tomažič, Jernej Vajda*, Boštjan Vižintin in Petra Vogrinec.</p>
	<p>* Kot mladoletne osebe neuradni ustanovitelji društva.</p>
@stop