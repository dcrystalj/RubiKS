Setup
--------------------------

* Clone a repository
	```clone -b dev https://github.com/dcrystalj/RubiKS.git```

* Create ```.env.local.php``` or ```.env.php``` file
	```php
	<?php
	return array(
	    'MYSQL_HOST' => 'localhost',
	    'MYSQL_DATABASE' => 'rubiks',
	    'MYSQL_USERNAME' => 'root',
	    'MYSQL_PASSWORD' => '',
	);
	```

	```php
	<?php
	return array(
		'MYSQL_HOST' =>			'localhost',
		'MYSQL_DATABASE' =>		'rubiks',
		'MYSQL_USERNAME' =>		'root',
		'MYSQL_PASSWORD' =>		'',

		'MAIL_HOST' =>			'',
		'MAIL_PORT' =>			25,
		'MAIL_USERNAME' =>		'',
		'MAIL_PASSWORD' =>		'',
		'MAIL_FROM_ADDRESS' =>	'',
		'MAIL_FROM_NAME' => 	'',

		'ENCRYPTION_KEY' =>		'',
	);
	```

* Run ```composer install```

* Create database (```rubiks```)

* Run migrations ```php artisan migrate```

* Make a symbolic link to public folder  
	```ln -s path-to-actual-folder name-of-link```,  
	to confirm, do:  
	```ls -ld name-of-link```

* Folders within app/storage require write access by the web server: ```sudo chmod -R o+w app/storage```

* Setup Nginx/Apache  
	Add the following lines to nginx .conf file:
	```
	location / {
	    try_files $uri $uri/ /index.php?$query_string;
	}
	```
Do not forget to RESTART server!