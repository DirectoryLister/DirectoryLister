<?php
//YOU MUST SET THE VARIABLES BELOW OR IT WONT WORK
$hostname = 'localhost';
$username = 'root';
$password = 'Password';

$con = mysql_connect($hostname,$username,$password);
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

if (mysql_query("CREATE DATABASE FileDownloads",$con))
  {
  echo "Database created";
  }
else
  {
  echo "Error creating database: " . mysql_error();
  }

mysql_select_db("FileDownloads", $con);

$dltbl = "CREATE TABLE downloadkey
(
uniqueid varchar(255) NOT NULL default '',
timestamp varchar(255) NOT NULL default '',
filename varchar(255) NOT NULL default '',
downloads varchar(255) NOT NULL default '0'
)";
mysql_query($dltbl,$con);

$md5tbl = "CREATE TABLE md5sums
(
filename varchar(255) NOT NULL default '',
md5 varchar(255) NOT NULL default ''
)";
mysql_query($md5tbl,$con);

$reftbl = "CREATE TABLE referer
(
referer varchar(255) NOT NULL default '',
count varchar(255) NOT NULL default '',
)";
mysql_query($reftbl,$con);

mysql_close($con);
echo "WARNING YOU MUST REMOVE THIS FILE OR SUFFER THE CONSEQUINCES!!!"
?>
