<div class="navbar navbar-default navbar-fixed-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="{{ url('/') }}">
				<div class="block">
					<img src="{{ URL::asset('img/rubiks.png') }}" height="26">
				</div>
				<div class="block">
					&nbsp; Rubik klub Slovenija
				</div>
			</a>
		</div>
		<div class="navbar-collapse collapse navbar-right">
			@if (Auth::guest())
				<ul class="nav navbar-nav">
					<li @if(Request::is('login', 'user/login')) class="active" @endif><a href="{{ url('login') }}">Prijava v sistem</a></li>
					<li @if(Request::is('competitions/future')) class="active" @endif><a href="{{ url('competitions/future') }}">Registracija</a></li>
				</ul>
			@else
				<ul class="nav navbar-nav">
					<li><a href="{{ route('competitors.show', Auth::user()->club_id) }}"><b>{{ Auth::user()->getFullName() }}</b></a></li>
					<li><a href="{{ route('registrations.index') }}">Prijave na tekmovanja</button></a></li>
					{{--<li><a href="{{ url('logout') }}"><span class="glyphicon glyphicon-off"></span> Odjava</a></li>--}}
					<li><a href="{{ url('logout') }}" title="Odjava"><span class="glyphicon glyphicon-off"></span></a></li>
				</ul>
			@endif
			
		</div><!--/.nav-collapse -->
	</div>
</div>