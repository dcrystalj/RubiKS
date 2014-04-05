@extends('main')
@section('content')
	<h4>Zahvale</h4>
	<?php $inRow = 3; ?>
	<table>
		@foreach ($credits as $i => $credit)
			@if ($i % $inRow == 0) <tr> @endif
			<td class="text-center credit">
				<b>{{ $credit->organization }}</b>
				<br>{{ str_replace(', ', ' <br>', $credit->address) }}<br>
				<a target="_blank" href="{{ $credit->url }}">{{ $credit->url }}</a>
			</td>
			@if ($i % $inRow == $inRow - 1) </tr> @endif
		@endforeach
		@if ($i % $inRow != $inRow - 1) </tr> @endif
	</table>
@stop