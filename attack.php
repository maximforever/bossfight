<?php

	function attack($playerid) {
		$query1 = "SELECT * FROM players WHERE playerid = '$playerid' ";
			$recordset = mysql_query($query1) or die (mysql_error());
			$row = mysql_fetch_array($recordset);

		$strength = $row["strength"];
		
		if ($strength > 0) {
			$attack = $strength;
		}
		else {
			$attack = 0;
		}

		$newstrength = $strength - 2;

		if ($newstrength < 0) {
			$newstrength = 0;
		}

	//---update stats---//
		$query2 = "UPDATE players SET strength = ('$newstrength') WHERE playerid = '$playerid' ";
			mysql_query($query2) or die (mysql_error());

		return $attack;
	}

?>