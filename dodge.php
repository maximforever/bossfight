<?php

	function dodge($playerid) {
		$query1 = "SELECT * FROM players WHERE playerid = '$playerid' ";
			$recordset = mysql_query($query1) or die (mysql_error());
			$row = mysql_fetch_array($recordset);

		$speed = $row["speed"];
		$dodge = $speed;

		$newspeed = $speed - 2;

		if ($newspeed < 0) {
			$newspeed = 0;
		}

	//---update stats---//
		$query2 = "UPDATE players SET speed = ('$newspeed') WHERE playerid = '$playerid' ";
			mysql_query($query2) or die (mysql_error());

		return $dodge;
	}

?>