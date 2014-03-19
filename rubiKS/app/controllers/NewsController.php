<?php

class NewsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$last5 = News::orderBy('created_at', 'desc')->take(5)->get();
		return View::make('news.index')->with('news', $last5);
	}

	public function archive()
	{
		$news = News::orderBy('created_at', 'desc')->get();
		return View::make('news.archive')->with('news', $news);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  str  $id
	 * @return Response
	 */
	public function show($id)
	{
		$article = News::where('url_slug', $id);

		if ($article->count() < 1) {
			return 'not found';
		}
		$article = $article->firstOrFail();

		return View::make('news.show')->with('article', $article);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}