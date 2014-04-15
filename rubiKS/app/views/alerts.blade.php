@if ( Session::get('error') )
	<div class="alert alert-error alert-danger">
		<b>
			@if ( is_array(Session::get('error')) )
				{{{ head(Session::get('error')) }}}
			@else
				{{{ Session::get('error') }}}
			@endif
		</b>
	</div>
@endif

@if ( Session::get('notice') )
	<div class="alert alert-warning"><b>{{{ Session::get('notice') }}}</b></div>
@endif

@if ( Session::get('info') )
	<div class="alert alert-info"><b>{{{ Session::get('info') }}}</b></div>
@endif

@if ( Session::get('success') )
	<div class="alert alert-success"><b>{{{ Session::get('success') }}}</b></div>
@endif