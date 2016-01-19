<?php

	include("config.php");

	$query1 = "SELECT * FROM games WHERE gameid = '$gameid' ";
		$recordset = mysql_query($query1) or die (mysql_error());
		$row = mysql_fetch_array($recordset);

	$gamestate = $row["gamestate"];
	$arrayplayersid = explode(",",$row["players"]);
		array_pop($arrayplayersid);
	$bosshealth = $row["bosshealth"];

	$weather = $row["weather"];

	//---prevent duplicative gameturns---//
	if($gamestate == "calculating") {
		$unset = 0;
		unset($unset);
	}
	else {
		$query2 = "UPDATE games SET gamestate = ('calculating') WHERE gameid = '$gameid' ";
			mysql_query($query2) or die (mysql_error());

	//---attack the boss---//
		$totalattack = 0;
		include("attack.php");

		foreach ($arrayplayersid as $playerid) {
			$query3 = "SELECT * FROM players WHERE playerid = '$playerid' ";
				$recordset = mysql_query($query3) or die (mysql_error());
				$row = mysql_fetch_array($recordset);
			$move = $row["playermove"];

			$raineffect = mt_rand(0,99);
			$snoweffect = mt_rand(0,99);
			if(($weather == "rainy") and ($raineffect < 5)) {
				//player attack fails
			}
			elseif(($weather == "snowy") and ($snoweffect < 20)) {
				//player attack fails
			}
			elseif($move == "attack") {
				$attack = attack($playerid);

				$windeffect = mt_rand(0,99);
				if (($weather == "windy") and ($windeffect < 20)) {
					$attack = ($attack * .8);
				}

				$totalattack = $totalattack + $attack;
			}
		}

		include("bossmove.php");
		$bossmove = boss_move($gameid);
		
	//---boss dodges---//
		if($bossmove == "dodge") {
			$totalattack = ($totalattack * .5);
		}

	//---boss takes damage---//
		$query2 = "SELECT * FROM games WHERE gameid = '$gameid' ";
			$recordset = mysql_query($query2) or die (mysql_error());
			$row = mysql_fetch_array($recordset);

		$bosshealth = $row["bosshealth"];
		$newbosshealth = ($bosshealth - $totalattack);

		$query3 = "UPDATE games SET bosshealth = ('$newbosshealth') WHERE gameid = '$gameid' ";
			mysql_query($query3) or die (mysql_error());

	//---boss deals damage---//
		if ($newbosshealth > 0) {
			$windeffect2 = mt_rand(0,99);
			$snoweffect2 = mt_rand(0,99);

		//---weather: wind fail---//
			if(($weather == "windy") and ($windeffect2 < 5)) {
				//boss attack fails
			}
			elseif(($weather == "snowy") and ($snoweffect2 < 20)) {
				//boss attack fails
			}

		//---attack one player---//
			elseif ($bossmove == "attackone") {
				include("bossattackone.php");
				include("dodge.php");

				$arraybossattack = explode(";",boss_attack_one($arrayplayersid));
				$target = $arraybossattack[0];
				$damage = $arraybossattack[1];

				$raineffect2 = mt_rand(0,99);
				if(($weather == "rainy") and ($raineffect2 < 20)) {
					$damage = ($damage * .8);
				}

				$query4 = "SELECT * FROM players WHERE playerid = '$target' ";
					$recordset = mysql_query($query4) or die (mysql_error());
					$row = mysql_fetch_array($recordset);
				$currenthealth = $row["health"];

			//---player dodges---//
				$move = $row["playermove"];
				if($move == "dodge") {
					$damage = ($damage - dodge($playerid));

					if($damage < 0) {
						$damage = 0;
					}
				}

			//---player takes damage---//
				$newhealth = ($currenthealth - $damage);
				$query5 = "UPDATE players SET health = ('$newhealth') WHERE playerid = '$target' ";
					mysql_query($query5) or die (mysql_error());
			}

		//---attack all players---//
			elseif ($bossmove == "attackall") {
				include("bossattackall.php");
				include("dodge.php");

				$arraybossattack = explode(";",boss_attack_all($arrayplayersid));
				$arraytargets = explode(",",$arraybossattack[0]);
				$damage = $arraybossattack[1];

				$raineffect2 = mt_rand(0,99);
				if(($weather == "rainy") and ($raineffect2 < 20)) {
					$damage = ($damage * .8);
				}

				foreach ($arraytargets as $target) {
					$query4 = "SELECT * FROM players WHERE playerid = '$target' ";
						$recordset = mysql_query($query4) or die (mysql_error());
						$row = mysql_fetch_array($recordset);
					$currenthealth = $row["health"];

				//---player dodges---//
					$move = $row["playermove"];
					if($move == "dodge") {
						$damage = ($damage - dodge($playerid));

						if($damage < 0) {
							$damage = 0;
						}
					}

				//---player takes damage---//
					$newhealth = ($currenthealth - $damage);
					$query5 = "UPDATE players SET health = ('$newhealth') WHERE playerid = '$target' ";
						mysql_query($query5) or die (mysql_error());
					}
				}
			}
		}

	//---players rest---//
		foreach ($arrayplayersid as $playerid) {
			$query6 = "SELECT * FROM players WHERE playerid = '$playerid' ";
				$recordset = mysql_query($query6) or die (mysql_error());
				$row = mysql_fetch_array($recordset);
			$move = $row["playermove"];

			if($move == "rest") {
				include("rest.php");
				rest($playerid);
			}
		}

	//---change weather---//
		include("changeweather.php");
		$newweather = change_weather($weather);

		$query7 = "UPDATE games SET weather = ('$newweather') WHERE gameid = '$gameid' ";
			mysql_query($query7) or die (mysql_error());

	//---reset gamestate---//
		foreach ($arrayplayersid as $playerid) {
			$query8 = "UPDATE players SET playermove = ('') WHERE playerid = '$playerid' ";
				mysql_query($query8) or die (mysql_error());

			$query9 = "UPDATE players SET playerstate = ('ready') WHERE playerid = '$playerid' ";
				mysql_query($query9) or die (mysql_error());
		}

		$query10 = "UPDATE games SET bossmove = ('') WHERE gameid = '$gameid' ";
			mysql_query($query10) or die (mysql_error());

		$query11 = "UPDATE games SET gamestate = ('ready') WHERE gameid = '$gameid' ";
			mysql_query($query11) or die (mysql_error());
	}

?>