<?php

return array(
	'title' => 'Rounds',
	'single' => 'round',
	'model' => 'Round',

	'permission' => function()
	{
		return Auth::user()->can('sudo');
	},

	'sort' => array(
		'field' => 'sort_key',
		'direction' => 'asc',
	),

	'columns' => array(
		'id',
		'short_name',
		'name',
		'sort_key',
	),

	'edit_fields' => array(
		'name',
		'short_name',
		'sort_key' => array(
			'type' => 'number',
		),
	)
);