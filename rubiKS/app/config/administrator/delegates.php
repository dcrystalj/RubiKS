<?php

return array(
	'title' => 'Delegates',
	'single' => 'delegate',
	'model' => 'Delegate',

	'columns' => array(
		'user_id' => array(
			'title' => 'User ID',
		),
		'name' => array(
			'title' => 'Name',
			'relationship' => 'user',
			'select' => "CONCAT((:table).name, ' ', (:table).last_name)",
		),
		'region' => array(
			'title' => 'Region',
		),
		'activity' => array(
			'title' => 'Activity',
		),
	),

	'edit_fields' => array(
		'user_id' => array(
			'title' => 'User ID',
		),
		'degree' => array(
			'title' => 'Degree',
		),
		'contact' => array(
			'title' => 'Contact',
		),
		'region' => array(
			'title' => 'Region',
		),
		'activity' => array(
			'type' => 'enum',
			'options' => array('0', '1')
		),
	)
);