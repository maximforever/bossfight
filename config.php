<?php
	$dbhost = 'localhost';
	$dbuser = 'root';
	$dbpass = '';
		mysql_connect($dbhost, $dbuser, $dbpass) or die (mysql_error());

	$dbname = 'bossfightdb';
		mysql_select_db($dbname);

?>