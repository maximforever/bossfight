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
		else {
			$playerid = 0;
			if(isset($_GET["game"])) {
				$gamecode = $_POST["game"];
				$query0 = "SELECT * FROM games WHERE gamecode = '$gamecode' ";
					$recordset = mysql_query($query0) or die (mysql_error());
					$row = mysql_fetch_array($recordset);
				$gameid = $row["gameid"];
			}
		}

	//---create game button---//
		if(isset($_POST["creategame"])) {
			$name = $_POST["name"];
			$browserinfo = $_SERVER['HTTP_USER_AGENT'];

			include("newgame.php");
			$gameid = new_game();

			$query1 = "SELECT * FROM games WHERE gameid = '$gameid' ";
				$recordset = mysql_query($query1) or die (mysql_error());
				$row = mysql_fetch_array($recordset);
			$gamecode = $row["gamecode"];

			include("newplayer.php");
			$playerid = new_player($name, $gameid, $browserinfo);
			$_SESSION["playerid"] = $playerid;

			header("location: leader.html?game=".$gamecode);
		}

	//---join game button---//
		elseif(isset($_POST["joingame"])) {
			$name = $_POST["name"];
			$gamecode = $_POST["joincode"];
			$browserinfo = $_SERVER['HTTP_USER_AGENT'];

			$query1 = "SELECT * FROM games WHERE gamecode = '$gamecode' ";
				$recordset = mysql_query($query1) or die (mysql_error());
				$row = mysql_fetch_array($recordset);

			$gameid = $row["gameid"];
				
			include("newplayer.php");
			$playerid = new_player($name, $gameid, $browserinfo);
			$_SESSION["playerid"] = $playerid;

			header("location: participant.html?game=".$gamecode);
		}

	//---start game button---//
		elseif(isset($_POST["startgame"]) and isset($_SESSION["playerid"])) {
			$gameid = start_game($gameid);
			$query1 = "SELECT * FROM games WHERE gamecode = '$gamecode' ";
				$recordset = mysql_query($query1) or die (mysql_error());
				$row = mysql_fetch_array($recordset);
			$gamecode = $row["gamecode"];
			header("location: main.html");
		}

	//---quit game button---//
		elseif(isset($_POST["quitgame"]) and isset($_SESSION["playerid"])) {
			$playerid = $_SESSION["playerid"];
			$_SESSION["playerid"] = quit_game($playerid);
			unset($_SESSION["playerid"]);
			header("location: index.html");
		}

	//---function start_game---//
		function start_game($gameid) {
			$query1 = "UPDATE games SET gamestate=('playerturn') WHERE gameid = '$gameid' ";
				mysql_query($query1) or die (mysql_error());

			$query2 = "SELECT * FROM players WHERE gameid =('$gameid') ";
				$recordset = mysql_query($query2) or die (mysql_error());

			$arrayplayersid = array("");
				array_pop($arrayplayersid);

			while ($row = mysql_fetch_array($recordset)) {
				$playerid = $row["playerid"];
				$arrayplayerid = array($playerid);
				$arrayplayersid = array_merge($arrayplayersid, $arrayplayerid);
			}

			$bosshealth = 0;
			foreach($arrayplayersid as $playerid) {
				$query3 = "UPDATE players SET playerstate =('playerturn') WHERE playerid = '$playerid' ";
				mysql_query($query3) or die (mysql_error());
				$bosshealth = $bosshealth + number("starting_boss_health_per_player");
			}

			$query4 = "UPDATE games SET bosshealth = ('$bosshealth') WHERE gameid = '$gameid' ";
				mysql_query($query4) or die (mysql_error());

			return $gameid;
		}

	//---function quit_game---//
		function quit_game($playerid) {
			$query5 = "SELECT * FROM players WHERE playerid = '$playerid' ";
				$recordset = mysql_query($query5) or die (mysql_error());
				$row = mysql_fetch_array($recordset);
			$gameid = $row["gameid"];

			$query6 = "DELETE FROM players WHERE playerid = '$playerid' AND gameid = '$gameid' ";
				mysql_query($query6) or die (mysql_error());

			$query7 = "SELECT * FROM games WHERE gameid = '$gameid' ";
				$recordset = mysql_query($query7) or die (mysql_error());
				$row = mysql_fetch_array($recordset);
			$players = $row["players"];

			$players = str_replace($playerid.",","",$players);

			if(empty($players)) {
				$query8 = "DELETE FROM games WHERE gameid = '$gameid' ";
					mysql_query($query8) or die (mysql_error());
			}
			else {
				$query9 = "UPDATE games SET players = REPLACE(players,'$playerid,','') WHERE gameid = '$gameid' ";
					mysql_query($query9) or die (mysql_error());
			}

			return $playerid;
		}
?>