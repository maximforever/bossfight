<?php
	
	function boss_attack_one($arrayplayersid);
		$arraytargets = array("");
			array_pop($arraytargets);

		foreach ($arrayplayersid as $playerid) {
			$query2 = "SELECT * FROM players WHERE playerid = '$playerid' ";
				$recordset = mysql_query($query2) or die (mysql_error());
				$row = mysql_fetch_array($recordset);
			$health = $row["health"];

			$x = 0;
			while ($x < $health) {
				$arraytargets = array_merge($arraytargets, $playerid);
				$x + 1;
			}
		}

		$randomtarget = array_rand($arraytargets, 1);
		$target = $arraytargets($randomtarget[0]);

		$attack = 8;

		return $target.";".$attack;
	}

?>