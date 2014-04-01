Setup
--------------------------

* Clone a repository
	```clone -b dev https://github.com/dcrystalj/RubiKS.git```

* Create ```.env.local.php``` file
	```php
	<?php
	return array(
	    'MYSQL_HOST' => 'localhost',
	    'MYSQL_DATABASE' => 'rubiks',
	    'MYSQL_USERNAME' => 'root',
	    'MYSQL_PASSWORD' => '',
	);
	```

* Run ```composer install```

* Create database (```rubiks```)

* Run migrations ```php artisan migrate```

* Make a symbolic link to public folder  
	```ln -s path-to-actual-folder name-of-link```,  
	to confirm, do:  
	```ls -ld name-of-link```

* Setup Nginx/Apache  
	Add the following lines to nginx .conf file:
	```
	location / {
	    try_files $uri $uri/ /index.php?$query_string;
	}
	```
Do not forget to RESTART server!