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
				$gameid = $_GET["game"];
			}
		}

	//---create game button---//
		if(isset($_POST["creategame"])) {
			$name = $_POST["name"];
			$browserinfo = $_SERVER['HTTP_USER_AGENT'];

			include("newgame.php");
			$gameid = new_game();

			include("newplayer.php");
			$playerid = new_player($name, $gameid, $browserinfo);
			$_SESSION["playerid"] = $playerid;

			header("location: leader.html?game=".$gameid);
		}

	//---join game button---//
		elseif(isset($_POST["joingame"])) {
			$name = $_POST["name"];
			$gameid = $_POST["joincode"];
			$browserinfo = $_SERVER['HTTP_USER_AGENT'];
				
			include("newplayer.html");
			$playerid = new_player($name, $gameid, $browserinfo);
			$_SESSION["playerid"] = $playerid;

			header("location: participant.html?game=".$gameid);
		}

	//---start game button---//
		elseif(isset($_POST["startgame"]) and isset($_SESSION["playerid"])) {
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
				$bosshealth = $bosshealth + 30;
			}

			$query4 = "UPDATE games SET bosshealth = ('$bosshealth') WHERE gameid = '$gameid' ";

			header("location: main.html?game=".$gameid);
		}

	//---quit game button---//
		elseif(isset($_POST["quitgame"]) and isset($_SESSION["playerid"])) {
			$playerid = $_SESSION["playerid"];
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

			unset($_SESSION["playerid"]);
		}

?>