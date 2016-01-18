<?php

	function rest($playerid) {
		$query1 = "SELECT * FROM players WHERE playerid = '$playerid' ";
			$recordset = mysql_query($query1) or die (mysql_error());
			$row = mysql_fetch_array($recordset);
		
		$health = $row["health"];
		$strength = $row["strength"];
		$speed = $row["speed"];

		$newhealth = $health + 4;
		$newstrength = $strength + 4;
		$newspeed = $speed + 4;

	//---update stats---//
		$query2 = "UPDATE players SET health = ('$newhealth') WHERE playerid = '$playerid' ";
			mysql_query($query2) or die (mysql_error());

		$query3 = "UPDATE players SET strength = ('$newstrength') WHERE playerid = '$playerid' ";
			mysql_query($query3) or die (mysql_error());

		$query4 = "UPDATE players SET speed = ('$newspeed') WHERE playerid = '$playerid' ";
			mysql_query($query4) or die (mysql_error());

		$query5 = "UPDATE players SET playerstate = ('gameturn') WHERE playerid = '$playerid' ";
			mysql_query($query5) or die (mysql_error());

		$rest = 4;
		return $rest;
	}

?>