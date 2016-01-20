<?php

	include("config.php");

	//---determine game & player---//
		if(isset($_SESSION["playerid"])) {
			$playerid = $_SESSION["playerid"];
			$query0 = "SELECT * FROM players WHERE playerid = '$playerid' ";
			$recordset = mysql_query($query0) or die (mysql_error());
			$row = mysql_fetch_array($recordset);

			$gameid = $row["gameid"];
		}

		if(isset($_POST["playermove"])) {
			$move = $_POST["playermove"];

		//--submit move---//
			$query1 = "UPDATE players SET playermove = ('$move') WHERE playerid = '$playerid' ";
				mysql_query($query1) or die (mysql_error());

			$query2 = "UPDATE players SET playerstate = ('waiting') WHERE playerid = '$playerid' ";
				mysql_query($query2) or die (mysql_error());

			include("status.php");
		}
?>