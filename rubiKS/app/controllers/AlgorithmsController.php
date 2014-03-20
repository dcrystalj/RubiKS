<?php

class AlgorithmsController extends \BaseController {

	public $algorithmsPath = 'files/algorithms';

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$path = public_path() . '/' . $this->algorithmsPath;
		
		if (!is_dir($path)) App::abort(404);
		if (!$handle = opendir($path)) App::abort(404);

		$contents = array();
		while (FALSE !== ($entry = readdir($handle))) {
	        if ($entry != "." && $entry != "..") $contents[] = $entry;
	    }
	    closedir($handle);

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
		$competition = Competition::where('short_name', $id);

		if ($competition->count() < 1) App::abort(404);
		$competition = $competition->first();

		$path = public_path() . '/' . $this->algorithmsPath . '/' . $competition->short_name;

		$contents = array();
		if (is_dir($path) AND $handle = opendir($path)) {
			while (FALSE !== ($entry = readdir($handle))) {
		        if ($entry != "." && $entry != "..") $contents[] = $entry;
		    }
		    closedir($handle);
		}

		return View::make('algorithms.show')->with('contents', $contents)->with('path', $this->algorithmsPath)->with('competition', $competition);
	}

}