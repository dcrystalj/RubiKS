<?php

return array(
	'title' => 'News',
	'single' => 'article',
	'model' => 'News',

	'permission' => function()
	{
		return Auth::user()->can('manage_news');
	},

	'columns' => array(
		'title' => array(
			'title' => 'Title',
		),
		'text' => array(
			'title' => 'Text',
		),
		'url_slug' => array(
			'title' => 'Link',
			'output' => function($value) { return '<a href="' . route('news.show', $value) . '" target="_blank">Link</a>'; },
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
		'url_slug' => array(
			'title' => 'URL slug'
		),
		'user_id' => array(
			'title' => 'Author'
		),
		'hidden' => array(
			'title' => 'Hidden',
			'type' => 'bool',
		),
		'sticky' => array(
			'title' => 'Sticky',
			'type' => 'bool',
		),
	)
);