<?php
define('DB_HOST', 'localhost');
define('DB_NAME','timesheet');
define('DB_USER','timesheet');
define('DB_PASSWORD','lUfa3mu8');
define('DB_DRIVER','mysql');
define('DB_DSN', DB_DRIVER . '://' . DB_USER . ':' . DB_PASSWORD . '@'. DB_HOST . '/' . DB_NAME);

$active_modules = array('Tasks');

?>