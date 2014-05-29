<?php

return array(
	'title' => 'Events',
	'single' => 'event',
	'model' => 'Event',

	'permission' => function()
	{
		return Auth::user()->can('sudo');
	},

	'columns' => array(
		'id',
		'name',
		'short_name',
		'readable_id',
		'show_average',
		'time_limit',
	),

	'edit_fields' => array(
		'readable_id',
		'short_name',
		'name',
		'attempts',
		'type',
		'show_average' => array(
			'type' => 'enum',
			'options' => array('0', '1')
		),
		'time_limit',
		'description' => array(
			'type' => 'textarea'
		),
		'help'
	)
);