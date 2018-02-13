<?php
// Kind of pointless script to make a db connection

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'codepurple');
define('DB_NAME', 'gotcha');

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if($link === false){
	die("Ayyy no connection could be made my dude. " . mysqli_connect_error());
}
?>
