<?php

return array(
	'title' => 'Notices',
	'single' => 'notice',
	'model' => 'Notice',

	'permission' => function()
	{
		return Auth::user()->can('manage_notices');
	},

	'columns' => array(
		'title' => array(
			'title' => 'Title',
		),
		'text' => array(
			'title' => 'Text',
		),
		'visible_until' => array(
			'title' => 'Visible until'
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
		'visible_until' => array(
			'title' => 'Visible until',
			'type' => 'date'
		)
	)
);