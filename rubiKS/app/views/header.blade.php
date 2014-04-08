<div class="header">
	<div class="container">
		<div class="col-sm-3">
			<img alt="Rubik klub Slovenija" src="http://www.rubik.si/klub/rubiks_logo.png" width="164">
		</div>
		<div class="col-sm-5">
			{{--<h1>RubiKS <small>Rubik Klub Slovenija</small></h1>--}}
			<h1>Rubik klub Slovenija</h1>
		</div>
		<div class="col-sm-4">
			<div class="well">
			@if (Auth::guest())
				Pozdravljeni! Trenutno niste prijavljeni.
				<a href="{{ url('login') }}"><button type="button" class="btn btn-xs btn-default">
					Prijava v sistem
				</button></a>
				<a href="{{ url('competitions/future') }}"><button type="button" class="btn btn-xs btn-default">
					Registracija
				</button></a>
			@else
				@if (Auth::user()->gender[0] === 'm')
					Pozdravljen,
				@else
					Pozdravljena,
				@endif
				<a href="{{ URL::to('competitors', Auth::user()->club_id) }}"><b>{{ Auth::user()->getFullName() }}</b></a>!
				<a href="{{ url('logout') }}"><button type="button" class="btn btn-xs btn-default">
					<span class="glyphicon glyphicon-off"></span> Odjava
				</button></a>
			@endif
			</div>
		</div>
	</div>
</div>