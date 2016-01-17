<?php

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

	//---create game button---//
		if(isset($_POST["creategame"])) {
			if(empty($_POST["name"]) {
				$nameError = "<div class='errormessage'>enter a name</div>";
			}
			elseif (!preg_match("/^[a-zA-Z0-9]*$/",$_POST["name"])) {
		 		$nameError = "<div class='errormessage'>only include letters & numbers</div>";
			}
			elseif (preg_match("/^[0-9]*$/",$_POST["name"])) {
				$nameError = "<div class='errormessage'>include letters too</div>";
			}
			elseif (strlen($_POST["name"]) > 16) {
				$nameError = "<div class='errormessage'>keep it 4-16 characters</div>";
			}
			elseif (strlen($_POST["name"]) < 4) {
				$nameError = "<div class='errormessage'>keep it 4-16 characters</div>";
			}
			else {
				$name = $_POST["name"];
				$browserinfo = $_SERVER['HTTP_USER_AGENT'];

				include("newgame.php");
				$gameid = newgame();

				include("newplayer.php");
				$playerid = new_player($name, $gameid, $browserinfo);
				$_SESSION["player"] = $playerid;

				header("location: leader.html");
			}
		}

	//---join game button---//
		if(isset($_POST["joingame"])) {
			$name = $_POST["name"];
			$gameid = $_POST["joincode"];
			$browserinfo = $_SERVER['HTTP_USER_AGENT'];
				
			include("newplayer.php");
			$playerid = new_player($name, $gameid, $browserinfo);
			$_SESSION["playerid"] = $playerid;

			header("location: participant.html");
		}

	//---start game button---//
		if(isset($_POST["startgame"])) {
			$query1 = "UPDATE games SET gamestate = ('waiting') WHERE gameid = '$gameid' ";
				mysql_query($query1) or die (mysql_error());

			$query2 = "SELECT * FROM players WHERE gameid = '$gameid' ";
				$recordset = mysql_query($query2) or die (mysql_error());

			$arrayplayersid = array("");
				array_pop($arrayplayersid);

			while ($row = mysql_fetch_array($recordset)) {
				$playerid = $row["playerid"];
				$arrayplayerid = array($playerid);
				$arrayplayersid = array_merge($arrayplayersid, $arrayplayerid);
			}

			foreach($arrayplayersid as $playerid) {
				$query3 = "UPDATE players SET playerstate = ('ready') WHERE playerid = '$playerid' ";
				mysql_query($query3) or die (mysql_error());
			}

			header("location: main.html");
		}

?>