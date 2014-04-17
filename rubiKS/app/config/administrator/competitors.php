<?php

// Compatibility fix - user was not being saved due to ˙unique` rule
User::$rules['email'] = 'required|email';

return array(
	'title' => 'Competitors',
	'single' => 'competitor',
	'model' => 'User',

	'columns' => array(
		'id',
		'full_name',
		'club_id',
		'email',
		'gender',
		'confirmed' => array(
			'output' => function($value) { return Help::checked($value); }
		),
	),

	'edit_fields' => array(
		'club_id',
		'name',
		'last_name',
		'email',
		'password',
		'confirmed' => array(
			'title' => 'Confirmed',
			'type' => 'enum',
			'options' => array('0', '1'),
		),
		'nationality',
		'city',
		'birth_date' => array(
			'type' => 'date',
		),
		/*'gender' => array(
			'title' => 'Gender',
			'type' => 'enum',
			'options' => array('m' => 'male', 'f' => 'female')
		),*/
		'notes',
		//'status',
		//'level',
		'joined_date',
		'banned_date',
		'forum_nickname',
		'club_authority',
		'membership_year',
	)
);