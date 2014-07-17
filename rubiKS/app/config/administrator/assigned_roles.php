<?php

return array(
	'title' => 'Assigned roles',
	'single' => 'assigned role',
	'model' => 'AssignedRole',

	'permission' => function()
	{
		return Auth::user()->can('sudo');
	},

	'columns' => array(
		'user_id' => array(
			'title' => 'User',
			'relationship' => 'user',
			'select' => "CONCAT((:table).name, ' ', (:table).last_name)",
		),
		'role_id' => array(
			'title' => 'Role',
			'relationship' => 'role',
			'select' => '(:table).name',
		),
	),

	'edit_fields' => array(
		'user_id' => array(
			'type' => 'number',
		),
		'role_id' => array(
			'type' => 'number',
		),
	)
);