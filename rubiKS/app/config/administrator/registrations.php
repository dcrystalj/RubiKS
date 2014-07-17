<?php

return array(
	'title' => 'Registrations',
	'single' => 'registration',
	'model' => 'Registration',

	'permission' => function()
	{
		return Auth::user()->can('manage_registrations');
	},

	'columns' => array(
		'competition' => array(
			'title' => 'Competition',
			'relationship' => 'competition',
			'select' => '(:table).name',
		),
		'name' => array(
			'title' => 'Name',
			'relationship' => 'user',
			'select' => "CONCAT((:table).name, ' ', (:table).last_name)",
		),
		'events' => array(
			'title' => 'Notes',
		),
		'notes' => array(
			'title' => 'Notes',
			'output' => function($value) { return htmlentities($value); },
		),
		'confirmed' => array(
			'title' => 'Confirmed',
			'output' => function($value) { return Help::checked($value); },
		),
		'created_at' => array(
			'title' => 'Created at',
		),
		'updated_at' => array(
			'title' => 'Updated at',
		),
	),

	'edit_fields' => array(
		'confirmed' => array(
			'title' => 'Confirmed',
			'type' => 'enum',
			'options' => array('0', '1'),
		),
		'events' => array(
			'title' => 'Events',
		),
		'notes' => array(
			'title' => 'Notes',
			'type' => 'textarea',
		)
	)
);