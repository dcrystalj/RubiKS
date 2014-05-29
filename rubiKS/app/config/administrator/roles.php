<?php

return array(
	'title' => 'Roles',
	'single' => 'role',
	'model' => 'Role',

	'permission' => function()
	{
		return Auth::user()->can('sudo');
	},

	'columns' => array(
		'id',
		'name',
	),

	'edit_fields' => array(
		'name',
	)
);