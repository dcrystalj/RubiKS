Laravel Deployment (on a shared hosting)
    1.  Upload everything except public/ into laravel/
    2.  Upload contents of public/ into public_html/
    3.  Fix path (2x) in index.php
    4.  Fix public dir in bootstrap/paths.php
    5.  Delete contents of app/storage/views/
    6.  Chmod app/storage to 777
    7.  Create .env.php file
