<?php

	function boss_attack($gameid) {
		$query1 = "SELECT * FROM games WHERE gameid = '$gameid' ";
		$recordset = mysql_query($query1) or die (mysql_error());
		$row = mysql_fetch_array($row);

		$players = $row["players"];
		$arrayplayersid = explode(",",$players);
			array_pop($arrayplayersid);

		$randomnumber = mt_rand(0,99);

		//---defend---//
			if ($randomnumber < 20) {
				$target = 0;
				$attack = 0;
			}

		//---attack all---//
			elseif (($randomnumber > 19) and ($randomnumber < 70)) {
				$target = "";

				foreach($arrayplayersid as $playerid) {
					$target = $target.$playerid.",";
				}

				$attack = 5;
			}
		
		//---attack one---//
			elseif ($randomnumber > 69) {

				$arrayplayerhealths = array("");
					array_pop($arrayplayerhealths);

				foreach ($arrayplayersid as $playerid) {
					$query2 = "SELECT * FROM players WHERE playerid = '$playerid' ";
					$recordset = mysql_query($query2) or die (mysql_error());
					$row = mysql_fetch_array($recordset);
					$health = $row["health"];

					$arrayplayer = array($playerid, $health);

					$arrayplayerhealths = array_merge($arrayplayerhealths, $arrayplayer);
				}

				$totalhealth = 0;

				foreach($arrayplayerhealths as $health) {
					$totalhealth = $totalhealth + $health[1];
				}

				$newrandomnumber = mt_rand(0,$totalhealth);
				///////where i paused//////
			}

		$boss_attack = array($target, $attack);
		return $boss_attack;
	}

?>