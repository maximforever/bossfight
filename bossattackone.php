<?php
	
	function boss_attack_one($arrayplayersid) {
		$arraytargets = array("");
			array_pop($arraytargets);

		foreach ($arrayplayersid as $playerid) {
			$query2 = "SELECT * FROM players WHERE playerid = '$playerid' ";
				$recordset = mysql_query($query2) or die (mysql_error());
				$row = mysql_fetch_array($recordset);
			$health = $row["health"];
			$arrayplayerid = array($playerid);

			$x = 0;
			while ($x < $health) {
				$arraytargets = array_merge($arraytargets, $arrayplayerid);
				$x = $x + 1;
			}
		}

		shuffle($arraytargets);
		$target = $arraytargets[0];

		$attack = mt_rand(2,10);

		return $target.";".$attack;
	}

?>