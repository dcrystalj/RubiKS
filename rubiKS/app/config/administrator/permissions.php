<?php

return array(
	'title' => 'Permissions',
	'single' => 'permission',
	'model' => 'Permission',

	'permission' => function()
	{
		return Auth::user()->can('sudo');
	},

	'columns' => array(
		'name',
		'display_name',
	),

	'edit_fields' => array(
		'name',
		'display_name',
	)
);