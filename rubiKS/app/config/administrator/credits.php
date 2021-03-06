<?php

return array(
	'title' => 'Credits',
	'single' => 'credit',
	'model' => 'Credit',

	'permission' => function()
	{
		return Auth::user()->can('sudo');
	},

	'columns' => array(
		'organization',
		'address',
		'url',
		'visible',
	),

	'edit_fields' => array(
		'organization',
		'address' => array(
			'type' => 'textarea'
		),
		'url',
		'visible' => array(
			'type' => 'enum',
			'options' => array('0', '1')
		),
	)
);