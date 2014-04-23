<ul class="nav nav-pills nav-stacked">
	<li @if(Request::is('/', 'news*')) class="active" @endif >
		<a href="{{ url('/') }}">
			<span class="glyphicon glyphicon-th-large"></span> Novice
		</a>
	</li>
	<li @if(Request::is('notices*')) class="active" @endif >
		<a href="{{ url('notices') }}">
			<span class="glyphicon glyphicon-pushpin"></span> Obvestila
		</a>
	</li>
	<li @if(Request::is('competitions*', 'algorithms*')) class="active" @endif >
		<a href="{{ url('competitions') }}">
			<span class="glyphicon glyphicon-map-marker"></span> Tekmovanja
		</a>
	</li>
	<li @if(Request::is('competitors*')) class="active" @endif >
		<a href="{{ url('competitors') }}">
			<span class="glyphicon glyphicon-user"></span> Tekmovalci
		</a>
	</li>
	<li @if(Request::is('rankings*')) class="active" @endif >
		<a href="{{ url('rankings') }}">
			<span class="glyphicon glyphicon-stats"></span> Rezultati
		</a>
	</li>
	<li @if(Request::is('records*')) class="active" @endif >
		<a href="{{ url('records') }}">
			<span class="glyphicon glyphicon-star"></span> Rekordi
		</a>
	</li>
	<li @if(Request::is('events*')) class="active" @endif >
		<a href="{{ url('events') }}">
			<span class="glyphicon glyphicon-tag"></span> Discipline
		</a>
	</li>
	<li>
		<a href="{{ url('members') }}"><span class="glyphicon glyphicon-list"></span> Člani kluba</a>
	</li>
	<li>
		<a href="{{ url('delegates') }}"><span class="glyphicon glyphicon-tree-deciduous"></span> Delegati</a>
	</li>
	<li>
		<a href="{{ url('static') }}">
			<span class="glyphicon glyphicon-info-sign"></span> Informacije
		</a>
	</li>
	<hr>
	<li>
		<a href="{{ url('credits') }}">
			<span class="glyphicon glyphicon-heart"></span> Zahvale
		</a>
	</li>
	<li>
		<a href="{{ url('http://www.rubik.si/') }}">
			<span class="glyphicon glyphicon-comment"></span> Forum
		</a>
	</li>
</ul>
<br>
{{--

<br>
--}}
