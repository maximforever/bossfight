<?php

	function attack($playerid) {
		$query1 = "SELECT * FROM players WHERE playerid = '$playerid' ";
			$recordset = mysql_query($query1) or die (mysql_error());
			$row = mysql_fetch_array($recordset);

		$strength = $row["strength"];
		$attack = $strength;

		$newstrength = $strength - 2;

	//---update stats---//
		$query2 = "UPDATE players SET strength = ('$newstrength') WHERE playerid = '$playerid' ";
			mysql_query($query2) or die (mysql_error());

		$query3 = "UPDATE players SET playerstate = ('waiting') WHERE playerid = '$playerid' ";
			mysql_query($query3) or die (mysql_error());

		return $attack;
	}

?>