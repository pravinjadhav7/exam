<?php
$dbhost = "localhost";
$dbuser = "pravin";
$dbpass = "shailay";
$dbname = "exam";
mysql_connect($dbhost,$dbuser,$dbpass) or die('cannot connect to the server'); 
mysql_select_db($dbname) or die('database selection problem');
?>