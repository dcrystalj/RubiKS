<?php

return array(
	'title' => 'Static Pages',
	'single' => 'static page',
	'model' => 'StaticPage',

	'permission' => function()
	{
		return Auth::user()->can('sudo');
	},

	'columns' => array(
		'title' => array(
			'title' => 'Title',
		),
		'text' => array(
			'title' => 'Text',
		),
		'url' => array(
			'title' => 'Link',
			'output' => function($value) { return '<a href="' . route('static.show', $value) . '" target="_blank">Link</a>'; },
		)
	),

	'form_width' => 600,

	'edit_fields' => array(
		'title' => array(
			'title' => 'Title',
		),
		'text' => array(
			'title' => 'Text',
			'type' => 'wysiwyg',
		),
		'url' => array(
			'title' => 'URL'
		),
	)
);