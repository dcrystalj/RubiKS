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

	'edit_fields' => array(
		'title' => array(
			'title' => 'Title',
		),
		'text' => array(
			'title' => 'Text',
			'type' => 'textarea',
		),
		'visible_until' => array(
			'title' => 'Visible until',
			'type' => 'date'
		)
	)
);