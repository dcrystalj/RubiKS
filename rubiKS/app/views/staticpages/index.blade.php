@extends('main')
@section('content')
	<h4>Seznam strani</h4>
	<ul>
	@foreach ($pages as $page)
		<li>
			<a href="{{ url('static', $page->url) }}">{{ $page->title }}</a> <br>
		</li>
	@endforeach
	</ul>
@stop