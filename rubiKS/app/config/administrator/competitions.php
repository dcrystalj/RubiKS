<?php

return array(
	'title' => 'Competitions',
	'single' => 'competition',
	'model' => 'Competition',

	'columns' => array(
		'id' => array(
			'title' => 'ID'
		),
		'short_name' => array(
			'title' => 'Short name'
		),
		'name' => array(
			'title' => 'Competition name'
		),
		'date' => array(
			'title' => 'Date'
		),
		'time' => array(
			'title' => 'Time'
		),
		'status' => array(
			'title' => 'Status',
			'output' => function($value)
			{
				if ($value == '1') {
					return '<b>Registrations opened</b>';
				} else if ($value == '0') {
					return '<b>Registrations closed</b>';
				} else if ($value == '-1-') {
					return 'Competition finished';
				}
			}
		)
	),

	'edit_fields' => array(
		'name' => array(
			'title' => 'Competition name'
		),
		'short_name' => array(
			'title' => 'Short name'
		),
		'date' => array(
			'title' => 'Date',
			'type' => 'date'
		),
		'time' => array(
			'title' => 'Time'
		),
		'max_competitors' => array(
			'title' => 'Max competitors'
		),
		'events' => array(
			'title' => 'Events'
		),
		'city' => array(
			'title' => 'City'
		),
		'venue' => array(
			'title' => 'Venue'
		),
		'organiser' => array(
			'title' => 'Organiser'
		),
		'delegate1' => array(
			'title' => 'Delegate 1'
		),
		'delegate2' => array(
			'title' => 'Delegate 2'
		),
		'delegate3' => array(
			'title' => 'Delegate 3'
		),
		'description' => array(
			'title' => 'Description',
			'type' => 'textarea'
		),
		'registration_fee' => array(
			'title' => 'Registration fee'
		),
		'country' => array(
			'title' => 'Country'
		),
		'championship' => array(
			'title' => 'championship',
			'type' => 'enum',
			'options' => array('1', '0'),
		),
		'status' => array(
			'title' => 'Status',
			'type' => 'enum',
			'options' => array('1', '0', '-1'),
		)
	)
);