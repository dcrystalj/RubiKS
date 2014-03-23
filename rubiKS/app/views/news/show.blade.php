@extends('main')
@section('content')
	<h2>{{ $article->title }} <small class="pull-right">{{ $article->getParsedDateShort() }}</small></h2>
	<span>
		{{ $article->text }}
	</span>
	<br>
	<span class="pull-right">Nazaj na <a href="{{ url('news') }}">arhiv novic</a>.</span>
@stop