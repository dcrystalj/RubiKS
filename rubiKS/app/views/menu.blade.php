<link rel='stylesheet' type='text/css' href='{{ URL::asset("assets/menu/styles.css") }}'>
<script type='text/javascript' src='{{ URL::asset("assets/menu/menu_jquery.js") }}'></script>
<div id='cssmenu'>
	<ul>
		<li><a href="{{ url('/') }}"><span class="glyphicon glyphicon-home"></span> Novice</a></li>
		<li><a href="{{ url('notices') }}"><span class="glyphicon glyphicon-pushpin"></span> Obvestila</a></li>

		<li class='has-sub'>
			<a href="#">
				<span class="glyphicon glyphicon-map-marker"></span> Tekmovanja
			</a>
			<ul>
				<li><a href="{{ url('competitions') }}"><span class="glyphicon glyphicon-th-list"></span> Pregled tekmovanj</a></li>
				<li><a href="{{ url('competitions/future') }}"><span class="glyphicon glyphicon-calendar"></span> Najave tekmovanj</a></li>
				<li><a href="{{ url('static/neuradne-izvedbe-tekmovanj') }}"><span class="glyphicon glyphicon-minus"></span> Neuradne izvedbe</a></li>
				<li><a href="{{ url('static/mednarodna-tekmovanja') }}"><span class="glyphicon glyphicon-globe"></span> Mednarodna tekmovanja</a></li>
			</ul>
		</li>

		<li>
			<a href="{{ url('national-championship') }}">
				<span class="glyphicon glyphicon-tower"></span> Državno prvenstvo
			</a>
		</li>
		
		<li><a href="{{ url('competitors') }}"><span class="glyphicon glyphicon-user"></span> Tekmovalci</a></li>
		<li><a href="{{ url('records') }}"><span class="glyphicon glyphicon-star"></span> Rekordi</a></li>
		<li><a href="{{ url('rankings') }}"><span class="glyphicon glyphicon-stats"></span> Rezultati</a></li>

		<li class='has-sub'>
			<a href="#">
				<span class="glyphicon glyphicon-book"></span> Pravila
			</a>
			<ul>
				<li><a href="{{ url('static/prijava-na-tekmo') }}">Prijava na tekmo</a></li>
				<li><a href="{{ url('static/tekmovalni-sistem') }}">Tekmovalni sistem</a></li>
				<li><a href="{{ url('events') }}">Discipline</a></li>
				<li><a href="{{ url('static/pravilniki') }}">Pravilniki</a></li>
			</ul>
		</li>

		<li class='has-sub'>
			<a href="#">
				<span class="glyphicon glyphicon-info-sign"></span> Informacije
			</a>
			<ul>
				<li><a href="{{ url('static/o-klubu') }}">O klubu</a></li>
				<li><a href="{{ url('members') }}">Člani kluba</a></li>
				<li><a href="{{ url('history-of-nr') }}">Zgodovina državnih rekordov</a></li>
				<li><a href="{{ url('delegates') }}">Delegati</a></li>
				<li><a href="{{ url('static/datoteke') }}">Datoteke</a></li>
				<li><a href="{{ url('news') }}">Arhiv novic</a></li>

				<li class='has-sub'>
					<a href="#">Multimedija</a>
					<ul>
						<li><a href="{{ url('https://picasaweb.google.com/100258962713596029232') }}" target="_blank">Fotogalerija</a></li>
						<li><a href="{{ url('https://www.youtube.com/user/RubikKlubSlovenija') }}" target="_blank">Videoteka</a></li>
					</ul>
				</li>

				<li class='has-sub'>
					<a href="#">Forum</a>
					<ul>
						<li><a href="{{ url('http://www.rubik.si/viewforum.php?f=30') }}" target="_blank">Teme o klubu</a></li>
						<li><a href="{{ url('http://www.rubik.si/viewforum.php?f=35') }}" target="_blank">Teme za člane</a></li>
					</ul>
				</li>

				<li><a href="{{ url('static/zunanje-povezave') }}">Zunanje povezave</a></li>

				<li class='has-sub'>
					<a href="#">Podprite kockanje!</a>
					<ul>
						<li><a href="{{ url('static/tekmujte') }}">Tekmujte</a></li>
						<li><a href="{{ url('static/vclanite-se-v-klub') }}">Včlanite se v klub</a></li>
						<li><a href="{{ url('static/podprite-nase-delo') }}">Podprite naše delo</a></li>
					</ul>
				</li>
			</ul>
		</li>
		<li><a href="{{ url('credits') }}"><span class="glyphicon glyphicon-heart"></span> Zahvale</a></li>
	</ul>
</div>
<hr>