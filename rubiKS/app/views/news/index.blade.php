@extends('main')
@section('content')
	<h4>Novice</h4>
	@foreach ($news as $article)
		<span class="news">
			<h2>{{ $article->title }} <small class="pull-right"><a href="{{ url('news', $article->url_slug) }}">#</a> {{ Help::dateTime($article->created_at, TRUE) }}</small></h2>
			<p>{{ $article->text }}</p>
		</span>
		<hr>
	@endforeach

	<a href="{{ url('news/archive') }}">Arhiv novic</a>
@stop