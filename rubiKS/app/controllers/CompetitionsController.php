<?php

class CompetitionsController extends \BaseController {

	public function index($without = FALSE)
	{
		if ($without === FALSE) {
			$competitions = Competition::orderBy('date', 'desc')->get();
		} else {
			$competitions = Competition::where('status', '<', '1')->orderBy('date', 'desc')->get();
		}

		return View::make('competition.index')->with('competitions', $competitions)->with('i', $competitions->count());
	}

	public function indexWithout()
	{
		return $this->index(TRUE);
	}

	public function show($id)
	{
		if (is_numeric($id)) {
			$competition = Competition::find($id);
		} else {
			$competition = Competition::where('short_name', $id);
		}
		$competition = $competition->firstOrFail();
		return dd($competition);
	}
}