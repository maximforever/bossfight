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
			if ($randomnumber < 20) {
				$move = "dodge";
			}

		//---attack all---//
			elseif (($randomnumber > 19) and ($randomnumber < 70)) {
				$move = "attackall";
			}
		
		//---attack one---//
			elseif ($randomnumber > 69) {
				$move = "attackone";
			}

		return $move;
	}

?>