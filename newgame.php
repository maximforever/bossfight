<?php

	function new_game() {
		$randomseed = substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', mt_rand(1,16))),0,16);

		$query1 = "INSERT INTO games (boss, weather, gamestate, roundcount) VALUES ('dragon','sunny','$randomseed', 0)";
			mysql_query($query1) or die (mysql_error());

		$query2 = "SELECT * FROM games WHERE gamestate = '$randomseed' ";
			$recordset = mysql_query($query2) or die (mysql_error());
			$row = mysql_fetch_array($recordset);

		$gameid = $row["gameid"];

		$query3 = "UPDATE games SET gamestate = ('setting up') WHERE gamestate = '$randomseed' AND gameid = '$gameid' ";
			mysql_query($query3) or die (mysql_error());

		return $gameid;
	}

?>