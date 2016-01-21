<?php

	function rest($playerid) {
		$query1 = "SELECT * FROM players WHERE playerid = '$playerid' ";
			$recordset = mysql_query($query1) or die (mysql_error());
			$row = mysql_fetch_array($recordset);
		
		$health = $row["health"];
		$strength = $row["strength"];
		$speed = $row["speed"];

		$newhealth = $health + mt_rand(number("rest_health_min"),number("rest_health_max"));
		$newstrength = $strength + mt_rand(number("rest_strength_min"),number("rest_strength_max"));
		$newspeed = $speed + mt_rand(number("rest_speed_min"),number("rest_speed_max"));

		if($newhealth > number("starting_health")) {
			$newhealth = number("starting_health");
		}
		if($newstrength > number("starting_strength")) {
			$newstrength = number("starting_strength");
		}
		if($newspeed > number("starting_speed")) {
			$newspeed = number("starting_speed");
		}

	//---update stats---//
		$query2 = "UPDATE players SET health = ('$newhealth') WHERE playerid = '$playerid' ";
			mysql_query($query2) or die (mysql_error());

		$query3 = "UPDATE players SET strength = ('$newstrength') WHERE playerid = '$playerid' ";
			mysql_query($query3) or die (mysql_error());

		$query4 = "UPDATE players SET speed = ('$newspeed') WHERE playerid = '$playerid' ";
			mysql_query($query4) or die (mysql_error());
	}

?>