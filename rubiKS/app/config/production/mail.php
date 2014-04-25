<?php

return array(
	'driver' => 'smtp',
	'host' => getenv('MAIL_HOST'),
	'port' => getenv('MAIL_PORT'),
	'from' => array('address' => getenv('MAIL_FROM_ADDRESS'), 'name' => getenv('MAIL_FROM_NAME')),
	'username' => getenv('MAIL_USERNAME'),
	'password' => getenv('MAIL_PASSWORD'),
	'pretend' => false,
);