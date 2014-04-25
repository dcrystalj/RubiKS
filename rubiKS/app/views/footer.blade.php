<div class="footer">
  <div class="container">
  	<div class="footer_block">
  		<img alt="RubiKS dejavnosti - pridružite se nam!" src="{{ URL::asset('assets/img/parola.png') }}" width="150">
  	</div>
  	<div class="footer_block">
    	<b>Rubik klub Slovenija</b> <br>
    	© Vse pravice pridržane
    </div>
  	<div class="footer_block">
  		<a href="{{ route('competitions.index') }}">Tekmovanja</a> <br>
      <a href="{{ route('competitors.index') }}">Tekmovalci</a> <br>
      <a href="{{ route('rankings.index') }}">Rezultati</a> <br>
      <a href="{{ url('records') }}">Rekordi</a> <br>
  	</div>
    <div class="footer_block">
    	<a href="{{ url('https://picasaweb.google.com/100258962713596029232') }}" target="_blank">Foto</a> <br>
    	<a href="{{ url('https://www.youtube.com/user/RubikKlubSlovenija') }}" target="_blank">Video</a> <br>
    	<a href="{{ url('http://www.rubik.si/') }}">Forum</a> <br>
      <a href="{{ route('news.index') }}">Arhiv novic</a> <br>
    </div>
    <div class="footer_block">
      <a href="{{ url('static/o-klubu') }}">O klubu</a> <br>
      <a href="{{ url('static/vclanite-se-v-klub') }}">Članstvo</a> <br>
      <a href="{{ url('static/pravilniki') }}">Pravila</a> <br>
      <a href="mailto:{{ HTML::email('info@rubik.si') }}">Kontakt</a> <br>
    </div>
  </div>
</div>