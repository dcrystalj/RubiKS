<?php

class StaticPagesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$pages = StaticPage::all();
		return View::make('staticpages.index')->withPages($pages);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  string  $url
	 * @return Response
	 */
	public function show($url)
	{
		$page = StaticPage::where('url', $url)->firstOrFail();
		return View::make('staticpages.show')->withPage($page);
	}

}