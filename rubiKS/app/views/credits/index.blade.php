@extends('main')
@section('content')
	<h4>Zahvale</h4>
	@foreach($credits as $credit)
		<div class="col-lg-4 text-center credit">
			<b>{{ $credit->organization }}</b>
			<br>{{ str_replace(', ', ' <br>', $credit->address) }}<br>
			<a target="_blank" href="{{ $credit->url }}">{{ $credit->url }}</a>
		</div>
	@endforeach
@stop