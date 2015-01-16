<?php

class AlgorithmsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$path = public_path() . '/' . self::algorithmsPath();
	    $contents = Dir::read($path);

		return View::make('algorithms.index')->with('contents', $contents);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  str  $id
	 * @return Response
	 */
	public function show($id)
	{
		$competition = Competition::getCompetitionByShortName($id);
		$path = public_path() . '/' . self::algorithmsPath() . '/' . $competition->short_name;
		$contents = Dir::read($path);
		sort($contents, SORT_STRING | SORT_NATURAL);

		return View::make('algorithms.show')->with('contents', $contents)->with('path', self::algorithmsPath())->with('competition', $competition);
	}

	public static function algorithmsPath()
	{
		return Config::get('paths')['algorithms'];
	}

}
