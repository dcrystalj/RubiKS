@extends('main')
@section('content')
	<h4>Obvestila</h4>
	@foreach ($notices as $notice)
		<span class="news">
			<h2>{{ $notice->title }} <small class="pull-right">{{ Date::dateTime($notice->created_at, TRUE) }}</small></h2>
			<p>{{ $notice->text }}</p>
			<p class="text-right"><small>Veljavno do: {{ Date::parse($notice->visible_until) }}</small></p>
		</span>
		<hr>
	@endforeach
@stop