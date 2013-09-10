<?php
//Your mysql host goes here (localhost unless your using a remote server)
$host = 'localhost';
//Your mysql username and password
$user = 'root';
$password = 'Password';
//The name of the database you are going to use
$dbname = 'FileDownloads';

// Connect to database  using: dbname , username , password
	$link = mysql_connect($host, $user, $password) or die("Could not connect: " . mysql_error());
	mysql_select_db($dbname) or die(mysql_error());
?>
