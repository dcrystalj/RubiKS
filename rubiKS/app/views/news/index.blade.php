@extends('main')
@section('content')
	<h4>Novice</h4>
	@foreach ($news as $article)
		<span class="news">
			<h2><a href="{{ route('news.show', $article->url_slug) }}">{{ $article->title }}</a> <small class="pull-right"><a href="{{ route('news.show', $article->url_slug) }}">#</a> {{ $article->getParsedDateShort() }}</small></h2>
			<p>{{ $article->text }}</p>
		</span>
		<hr>
	@endforeach

	<a href="{{ route('news.index') }}">Arhiv novic</a>
@stop