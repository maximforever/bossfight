<?php

	include("config.php");

	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}

	//---player screen---//
		if((isset($_SESSION["playerid"])) and ($_SESSION["playerid"] > 0)) {
			$playerid = $_SESSION["playerid"];
			$query0 = "SELECT * FROM players WHERE playerid = '$playerid' ";
				$recordset = mysql_query($query0) or die (mysql_error());
				$row = mysql_fetch_array($recordset);

		//---check whose turn it is---//
			$gameid = $row["gameid"];
			$whoseturn = whose_turn($gameid);
			if($whoseturn == "gameturn") {
				include("gamemove.php");
			}

		//---send player status---//
			$arrayplayerstatus = player_status($playerid);
			$playerstatus = $arrayplayerstatus[0].";".$arrayplayerstatus[1].";".$arrayplayerstatus[2].";".$arrayplayerstatus[3].";".$arrayplayerstatus[4];
			echo $playerstatus;
		}

	//---observer screen---//
		else {
			$playerid = 0;
			if(isset($_POST["game"])) {
				$gameid = $_POST["game"];

			//---check whose turn it is---//
				$whoseturn = whose_turn($gameid);
				if($whoseturn == "gameturn") {
					include("gamemove.php");
				}

			//---send game status---//
				$arraygamestatus = game_status($gameid);
				$gamestatus = $arraygamestatus[0].";".$arraygamestatus[1]."; ; ;".$arraygamestatus[4].";".$arraygamestatus[5];

				$players = "";
				$arrayplayers = $arraygamestatus[5];
				foreach($arrayplayers as $player) {
					$players = $players.$player.",";
				}

				$gamestatus = $gamestatus."; ;".$players;
				echo $gamestatus;
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

			$playerstatus = array($name,$health,$strength,$speed,$playerstate, "", "", "");
			return $playerstatus;
		}

	//---game status---//
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

			$gamestatus = array($boss, $bosshealth, "", "", $gamestate, $weather, $arrayplayersid, $arrayplayers);
			return $gamestatus;
		}

	//---whose turn---//
		function whose_turn($gameid) {
			$query1 = "SELECT * FROM games WHERE gameid = '$gameid' ";
				$recordset = mysql_query($query1) or die (mysql_query());
				$row = mysql_fetch_array($recordset);

			$arrayplayers = explode(",",$row["players"]);

			$arrayplayerstates = array("");
				array_pop($arrayplayerstates);
			
			foreach($arrayplayers as $playerid) {
				$query2 = "SELECT * FROM players WHERE playerid = '$playerid' ";
					$recordset = mysql_query($query2) or die (mysql_error());
					$row = mysql_fetch_array($recordset);

				$playerstate = $row["playerstate"];
				$arrayplayerstate = array($playerstate);
				$arrayplayerstates = array_merge($arrayplayerstates,$arrayplayerstate);
			}

			if(!in_array("playerturn", $arrayplayerstates)) {
				return "gameturn";
			}
			else {
				return "playerturn";
			}
		}	

?>