<?php

	function new_player($name, $gameid, $browserinfo) {
		$randomseed = substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', mt_rand(1,16))),0,16);

		$starting_health = number("starting_health");
		$starting_strength = number("starting_strength");
		$starting_speed = number("starting_speed");

		$query1 = "INSERT INTO players (name, gameid, health, strength, speed, playerstate, browserinfo) VALUES ('$name','$gameid','$starting_health','$starting_speed','$starting_strength','$randomseed','$browserinfo')";
			mysql_query($query1) or die (mysql_error());

		$query2 = "SELECT * FROM players WHERE name = '$name' AND playerstate = '$randomseed' ";
			$recordset = mysql_query($query2) or die (mysql_error());
			$row = mysql_fetch_array($recordset);
			$playerid = $row["playerid"];

		$query3 = "UPDATE players SET playerstate = ('setting up') WHERE playerid = '$playerid' AND playerstate = '$randomseed' ";
			mysql_query($query3) or die (mysql_error());

		$query4 = "UPDATE games SET players = CONCAT(players,'$playerid,') WHERE gameid = '$gameid' ";
			mysql_query($query4) or die (mysql_error());

		return $playerid;
	}

?>