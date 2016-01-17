<?php

	include("config.php");

	//---determine game & player---//
		if(isset($_SESSION["player"])) {
			$playerid = $_SESSION["playerid"];
			$query0 = "SELECT * FROM players WHERE playerid = '$playerid' ";
			$recordset = mysql_query($query1) or die (mysql_error());
			$row = mysql_fetch_array($recordset);

			$gameid = $row["gameid"];
		}
		else {
			$playerid = 0;
			if(isset($_GET["game"])) {
				$gameid = $_GET["game"];
			}
		}

	//---player status---//
		function player_status($playerid) {
			$query1 = "SELECT * FROM players WHERE playerid = '$playerid' ";
				$recordset = mysql_query($query1) or die (mysql_error());
				$row = mysql_fetch_array($recordset);

			$name = $row["name"];
			$health = $row["health"];
			$strength = $row["strength"];
			$speed = $row["speed"];
			$playerstate = $row["playerstate"];

			$playerstatus = array($health,$strength,$speed,$playerstate);
			return $playerstatus;
		}

	//---game state---//
		function game_status($gameid) {
			$query1 = "SELECT * FROM games WHERE gameid = '$gameid' ";
				$recordset = mysql_query($query1) or die (mysql_error());
				$row = mysql_fetch_array($recordset);

			$boss = $row["boss"];
			$bosshealth = $row["bosshealth"];
			$weather = $row["weather"];
			$gamestate = $row["gamestate"];
			$players = $row["players"];

			$arrayplayersid = explode(",",$players);
				array_pop($arrayplayersid);
			$arrayplayers = array("");
				array_pop($arrayplayers);

			foreach($arrayplayersid as $playerid) {
				$query2 = "SELECT * FROM players WHERE playerid = '$playerid' ";
					$recordset = mysql_query($query2) or die (mysql_error());
					$row = mysql_fetch_array($recordset);
					$name = $row["name"];

				$arrayname = array($name);
				$arrayplayers = array_merge($arrayplayers, $arrayname);
			}

			$gamestatus = array($boss, $bosshealth, $weather, $gamestate, $arrayplayersid, $arrayplayers);
			return $gamestatus;
		}

?>