<?php

class RegistrationsController extends \BaseController {

	/**
	 *
	 */
	public function __construct()
	{
		$this->beforeFilter('auth');
		$this->beforeFilter('registrations.createStore', [ 'only' => ['create', 'store'] ]);
		$this->beforeFilter('registrations.editUpdate', [ 'only' => ['edit', 'update'] ]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// List of all user's registrations
		$registrations = Auth::user()->registrations;

		// And links to future competitions
		$competitions = Competition::where('status', 1)->get();

		return View::make('registrations.index')->withRegistrations($registrations)->withCompetitions($competitions);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$competition = Competition::getCompetitionByShortName(Input::old('competition'));
		$events = Competition::getEvents($competition->events);

		return View::make('registrations.create')->withCompetition($competition)->withEvents($events);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$competition = Competition::getCompetitionByShortName(Input::get('competition'));
		$user = Auth::user();

		$registration = new Registration;
		$registration->user_id = $user->id;
		$registration->competition_id = $competition->id;
		$registration->events = Registration::parseSelectedEvents($competition, Input::all());
		$registration->notes = Input::get('notes');

		if (!$registration->save()) {
			return Redirect::to('registrations/' . $competition->short_name)->withNotice('Pri shranjevanju prijave je prišlo do napake.');
		}
		return Redirect::to('registrations/' . $competition->short_name)->withSuccess('Vaša prijava je bila shranjena.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($competitionShortName)
	{
		$competition = Competition::getCompetitionByShortName($competitionShortName);
		$registration = Registration::getRegistration(Auth::user()->id, $competition->id);

		if ($registration === null) {
			return Redirect::to('registrations/create')->withInput(['competition' => $competitionShortName]);
		}

		return View::make('registrations.show')->withRegistration($registration)->withCompetition($competition);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($competitionShortName)
	{
		$competition = Competition::getCompetitionByShortName($competitionShortName);
		$events = Competition::getEvents($competition->events);
		$registration = Registration::getRegistration(Auth::user()->id, $competition->id);

		// Load old data
		$selectedEvents = Competition::getEvents($registration->events, TRUE);
		foreach ($events as $event) {
			if (in_array($event->readable_id, $selectedEvents)) Input::merge([ 'event_' . $event->readable_id => '1' ]);
		}
		Input::merge([ 'notes' => $registration->notes ]);
		Input::flash();

		return View::make('registrations.edit')->withRegistration($registration)->withCompetition($competition)->withEvents($events);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($competitionShortName)
	{
		$competition = Competition::getCompetitionByShortName($competitionShortName);
		$registration = Registration::getRegistration(Auth::user()->id, $competition->id);
		$registration->events = Registration::parseSelectedEvents($competition, Input::all());
		$registration->notes = Input::get('notes');

		$registration->save();

		return Redirect::to('registrations/' . $competition->short_name)->withSuccess('Vaša prijava je bila posodobljena.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($competitionShortName)
	{
		$competition = Competition::getCompetitionByShortName($competitionShortName);
		$registration = Registration::getRegistration(Auth::user()->id, $competition->id);

		if ($registration != null) {
			if ($registration->delete()) {
				return Redirect::to('registrations')->withSuccess('Vaša prijava je bila uspešno izbrisana.');
			}
		}
	}

}
