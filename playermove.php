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

	//---determine move---//
		if(isset($_POST["attack"])) {
			include("attack.php");
			$attack = attack($playerid);
		}
		elseif(isset($_POST["dodge"])) {
			include("dodge.php"));
			$dodge = dodge($playerid);
		}
		elseif(isset($_POST["rest"])) {
			include("rest.php");
			$rest = rest($playerid);
		}

?>