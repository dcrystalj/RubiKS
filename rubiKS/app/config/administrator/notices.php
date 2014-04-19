<?php

return array(
	'title' => 'Notices',
	'single' => 'notice',
	'model' => 'Notice',

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