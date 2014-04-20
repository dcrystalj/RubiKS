<?php

class NoticesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$notices = Notice::where('visible_until', '>=', DB::Raw('CURDATE()'))->orderBy('created_at', 'desc')->get();
		return View::make('notices.index')->with('notices', $notices);
	}

}