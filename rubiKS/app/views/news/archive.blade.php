@extends('main')
@section('content')
	<h4>Arhiv novic</h4>
	@foreach ($news as $article)
		{{ $article->getParsedDateShort() }}: <a href="{{ url('news', $article->url_slug) }}">{{ $article->title }}</a> <br>
	@endforeach
@stop