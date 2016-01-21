<?php

	function boss_move($gameid) {
		$query1 = "SELECT * FROM games WHERE gameid = '$gameid' ";
			$recordset = mysql_query($query1) or die (mysql_error());
			$row = mysql_fetch_array($recordset);

		$players = $row["players"];
		$arrayplayersid = explode(",",$players);
			array_pop($arrayplayersid);

		$randomnumber = mt_rand(0,99);

		//---defend---//
			if ($randomnumber < number("percentage_boss_dodge")) {
				$move = "dodge";
			}

		//---attack all---//
			elseif (($randomnumber > (number("percentage_boss_dodge")-1)) and ($randomnumber < (number("percentage_boss_dodge") + number("percentage_boss_attack_all")))) {
				$move = "attackall";
			}
		
		//---attack one---//
			elseif ($randomnumber > (99 - number("percentage_boss_attack_one"))) {
				$move = "attackone";
			}

		return $move;
	}

?>