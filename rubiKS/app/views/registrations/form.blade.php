<div class="form-group">
    <label class="col-sm-2 control-label" for="competitionName">Prijavljate se na</label>
    <div class="col-sm-10">
        <span class="form-control">{{ $competition->name }} ({{ $competition->short_name }})</span>
    </div>
    <input type="hidden" name="competition" value="{{ $competition->short_name }}">
</div>

<div class="form-group">
    <label class="col-sm-2 control-label" for="events">Izberite discipline *</label>
    <div class="col-sm-10">
        @foreach($events as $event)
            <div class="checkbox">
                <label>
                    <input name="event_{{ $event->readable_id }}" id="event_{{ $event->readable_id }}" type="checkbox" value="1"> <b>{{ $event->short_name }}</b> ({{ $event->name }})
                </label>
            </div>
        @endforeach
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label" for="notes">Opombe</label>
    <div class="col-sm-10">
        <input class="form-control" placeholder="Opombe" type="text" name="notes" id="notes" value="{{{ Input::old('notes') }}}">
    </div>
</div>