@extends('main')
@section('content')
	<h4>{{ $page->title }}</h4>
	{{ $page->text }}
	<br>
	<div class="text-right"><small>Nazadnje posodobljeno: {{ Date::dateTime($page->updated_at, TRUE) }}</small></div>
@stop