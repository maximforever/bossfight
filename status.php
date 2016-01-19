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
			$gameid = $row["gameid"];

		//---check whose turn it is---//
			$whoseturn = whose_turn($gameid);
			if($whoseturn == "gameturn") {
				include("gamemove.php");
				$progress = game_move($gameid);

				if($progress = "calculating") {
					$gamestatus = game_status($playerid,$gameid);
					echo $gamestatus; 
				}
				elseif($progrss = "complete") {
					$gamestatus = game_status($playerid,$gameid);
					echo $gamestatus;
				}
			}
		
		//---send player status---//
			else {
				$gamestatus = game_status($playerid,$gameid);
				echo $gamestatus;
			}
		}

	//---observer screen---//
		elseif(isset($_POST["game"])) {
			$playerid = 0;
			$gameid = $_POST["game"];

		//---check whose turn it is---//
			$whoseturn = whose_turn($gameid);
			if($whoseturn == "gameturn") {
				include("gamemove.php");
				$progress = game_move($gameid);

				if($progress = "calculating") {
					$gamestatus = game_status($playerid,$gameid);
					echo $gamestatus; 
				}
				elseif($progrss = "complete") {
					$gamestatus = game_status($playerid,$gameid);
					echo $gamestatus;
				}
			}

		//---send game status---//
			else {
				$gamestatus = game_status(0,$gameid);
				echo $gamestatus;
			}
		}

	//---game status---//
		function game_status($playerid,$gameid) {

			$query0 = "SELECT * FROM games WHERE gameid = '$gameid' ";
				$recordset = mysql_query($query0) or die (mysql_error());
				$row = mysql_fetch_array($recordset);
		
		//---game stats---//
			$boss = $row["boss"];
			$bosshealth = $row["bosshealth"];
			$weather = $row["weather"];
			$bossmove = $row["bossmove"];
			$gamestate = $row["gamestate"];
			$arrayplayersid = explode(",",$row["players"]);
				array_pop($arrayplayersid);

		//---player stats---//
			if($playerid > 0) {
				$query1 = "SELECT * FROM players WHERE playerid = '$playerid' ";
					$recordset = mysql_query($query1) or die (mysql_error());
					$row = mysql_fetch_array($recordset);

				$name = $row["name"];
				$health = $row["health"];
				$strength = $row["strength"];
				$speed = $row["speed"];
				$playermove = $row["playermove"];
				$playerstate = $row["playerstate"];

				$arrayplayerid = array($playerid);
				$arrayplayersid = array_diff($arrayplayersid,$arrayplayerid);
			}
			else {
				$name = $boss;
				$health = 0;
				$strength = 0;
				$speed = 0;
				$playermove = 0;
				$playerstate = 0;
			}

		//---players list---//
			$arrayplayernames = array("");
				array_pop($arrayplayernames);
			foreach ($arrayplayersid as $playerid) {
				$query2 = "SELECT * FROM players WHERE playerid = '$playerid' ";
					$recordset = mysql_query($query2) or die (mysql_error());
					$row = mysql_fetch_array($recordset);
				$arrayplayername = array($row["name"]);
				$arrayplayernames = array_merge($arrayplayernames, $arrayplayername);
			}

			$playernames = "";
			foreach ($arrayplayernames as $playername) {
				$playernames = $playernames.$playername.",";
			}
			$playernames = substr($playernames,0,-1);

			return
				$gameid.";". //0
				$boss.";". //1
				$bosshealth.";". //2
				$weather.";". //3
				$bossmove.";". //4
				$gamestate.";". //5
				$name.";". //6
				$health.";". //7
				$strength.";". //8
				$speed.";". //9
				$playermove.";". //10
				$playerstate.";". //11
				$playernames; //12
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