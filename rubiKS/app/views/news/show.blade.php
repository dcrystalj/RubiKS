@extends('main')
@section('content')
	<h2>{{ $article->title }} <small class="pull-right">{{ Help::dateTime($article->created_at, TRUE) }}</small></h2>
	<span>
		{{ $article->text }}
	</span>
	<br>
	<span class="pull-right">Nazaj na <a href="{{ url('news/archive') }}">arhiv novic</a>.</span>
@stop