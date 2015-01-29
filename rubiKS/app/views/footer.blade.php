<div class="footer">
  <div class="container">
  	<div class="footer_block col-xs-6 col-sm-4 col-md-3">
  		{{--<img alt="RubiKS dejavnosti - pridružite se nam!" src="{{ URL::asset('assets/img/parola.png') }}" width="150">--}}
      <img alt="RubiKS dejavnosti - pridružite se nam!" src="{{ URL::asset('assets/img/rubiks_grb.png') }}" height="100">
  	</div>
  	<div class="footer_block col-xs-6 col-sm-8 col-md-3">
    	<b>Rubik klub Slovenija</b> <br>
    	© Vse pravice pridržane
      <br><br><small>Avtor: <a href="http://urosh.net" target="_blank" style="color: #333 !important;">Uroš Hekić</a></small><br><br>
      {{--<br><br><br> dirty trick :) fix this! --}}
    </div>
  	<div class="footer_block col-xs-4 col-md-2">
  		<a href="{{ route('competitions.index') }}">Tekmovanja</a> <br>
      <a href="{{ action('NationalChampionshipController@getIndex') }}">Prvenstvo</a> <br>
      <a href="{{ route('competitors.index') }}">Tekmovalci</a> <br>
      <a href="{{ route('rankings.index') }}">Rezultati</a> <br>
      <a href="{{ url('records') }}">Rekordi</a> <br>
  	</div>
    <div class="footer_block col-xs-4 col-md-2">
      <a href="{{ route('news.index') }}">Arhiv novic</a> <br>
      <a href="{{ route('notices.index') }}">Obvestila</a> <br>
      <a href="{{ url('http://www.rubik.si/forum/') }}">Forum</a> <br>
      <a href="{{ url('https://picasaweb.google.com/100258962713596029232') }}" target="_blank">Foto</a> <br>
      <a href="{{ url('https://www.youtube.com/user/RubikKlubSlovenija') }}" target="_blank">Video</a> <br>
    </div>
    <div class="footer_block col-xs-4 col-md-2">
      <a href="{{ url('static/o-klubu') }}">O klubu</a> <br>
      <a href="{{ url('static/vclanite-se-v-klub') }}">Članstvo</a> <br>
      <a href="{{ url('static/pravilniki') }}">Pravila</a> <br>
      <a href="{{ url('static/prijava-na-tekmo') }}">O registraciji</a> <br>
      <a href="mailto:{{ HTML::email('info@rubik.si') }}">Kontakt</a> <br>
    </div>
  </div>
</div>
