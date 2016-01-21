<?php

	function heal($playerid) {
		$query1 = "SELECT * FROM players WHERE playerid = '$playerid' ";
			$recordset = mysql_query($query1) or die (mysql_error());
			$row = mysql_fetch_array($recordset);
		$gameid = $row["gameid"];

	//---build heal targets---//
		$query2 = "SELECT * FROM games WHERE gameid = '$gameid' ";
			$recordset = mysql_query($query2) or die (mysql_error());	
			$row = mysql_fetch_array($recordset);
		$arraytargetsid = explode(",",$row["players"]);

		$arrayplayerid = array($playerid);
		$arraytargetsid = array_diff($arraytargetsid, $arrayplayerid);

		$healhealth = mt_rand(number("rest_health_min"),number("rest_health_max"));
		$healstrength = mt_rand(number("rest_strength_min"),number("rest_strength_max"));
		$healspeed = mt_rand(number("rest_speed_min"),number("rest_speed_max"));

	//---heal targets---//
		foreach($arraytargetsid as $targetid) {
			$query3 = "SELECT * FROM players WHERE playerid = '$targetid' ";
				$recordset = mysql_query($query3) or die(mysql_error());
				$row = mysql_fetch_array($recordset);
			$health = $row["health"];
			$strength = $row["strength"];
			$speed = $row["speed"];

			if($health > 0) {
				$newhealth = $health + $healhealth;
				$newstrength = $strength + $healstrength;
				$newspeed = $speed + $healspeed;
				
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
				$query2 = "UPDATE players SET health = ('$newhealth') WHERE playerid = '$targetid' ";
					mysql_query($query2) or die (mysql_error());

				$query3 = "UPDATE players SET strength = ('$newstrength') WHERE playerid = '$targetid' ";
					mysql_query($query3) or die (mysql_error());

				$query4 = "UPDATE players SET speed = ('$newspeed') WHERE playerid = '$targetid' ";
					mysql_query($query4) or die (mysql_error());
			}
		}
	}

?>