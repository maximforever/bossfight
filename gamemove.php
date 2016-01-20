<?php

	include("bossmove.php");
	include("attack.php");
	include("bossattackone.php");
	include("bossattackall.php");
	include("dodge.php");
	include("rest.php");
	include("changeweather.php");

//---function game_move---//
	function game_move($gameid) {
		$query1 = "SELECT * FROM games WHERE gameid = '$gameid' ";
			$recordset = mysql_query($query1) or die (mysql_error());
			$row = mysql_fetch_array($recordset);

		$gamestate = $row["gamestate"];
		$arrayplayersid = explode(",",$row["players"]);
			array_pop($arrayplayersid);
		$bosshealth = $row["bosshealth"];
		$boss = $row["boss"];
		$roundcount = $row["roundcount"];
		$weather = $row["weather"];

		//---prevent duplicative gameturns---//
		if($gamestate == "calculating") {
			return "calculating";
		}
		else {
			$query2 = "UPDATE games SET gamestate = ('calculating') WHERE gameid = '$gameid' ";
				mysql_query($query2) or die (mysql_error());
			$story = "";

		//---attack the boss---//
			$totalattack = 0;

			foreach ($arrayplayersid as $playerid) {
				$query3 = "SELECT * FROM players WHERE playerid = '$playerid' ";
					$recordset = mysql_query($query3) or die (mysql_error());
					$row = mysql_fetch_array($recordset);
				$move = $row["playermove"];

				$raineffect = mt_rand(0,99);
				$snoweffect = mt_rand(0,99);
				if(($move == "attack") and ($weather == "rainy") and ($raineffect < 5)) {
					$story = $story."Rain prevents @".$playerid."&'s attack.,"; //story
				}
				elseif(($move == "attack") and ($weather == "snowy") and ($snoweffect < 20)) {
					$story = $story."Snow prevents @".$playerid."&'s attack.,"; //story
				}
				elseif($move == "attack") {
					$attack = attack($playerid);

					$windeffect = mt_rand(0,99);
					if (($weather == "windy") and ($windeffect < 20)) {
						$attack = ($attack * .8);
						$story = $story."Wind reduces @".$playerid."&'s attack to ".$attack." damage.,"; //story
					}
					else {
						$story = $story."@".$playerid."& attacks for ".$attack." damage.,"; //story
					}

					$totalattack = $totalattack + $attack;
				}
			}

			$bossmove = boss_move($gameid);
			
		//---boss dodges---//
			if($bossmove == "dodge") {
				$totalattack = ($totalattack * .5);
				$story = $story."The ".$boss." dodges to escape half the attack.,"; //story
			}

		//---boss takes damage---//
			$story = $story."The ".$boss." takes ".$totalattack." damage.,"; //story

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
				if((($bossmove == "attackone") or ($bossmove == "attackall")) and ($weather == "windy") and ($windeffect2 < 5)) {
					$story = $story."The wind causes the ".$boss."'s attack to miss.,"; //story
				}
				elseif((($bossmove == "attackone") or ($bossmove == "attackall")) and ($weather == "snowy") and ($snoweffect2 < 20)) {
					$story = $story."The snow causes the ".$boss."'s attack to miss.,"; //story
				}

			//---attack one player---//
				elseif ($bossmove == "attackone") {
					$arraybossattack = explode(";",boss_attack_one($arrayplayersid));
					$target = $arraybossattack[0];
					$damage = $arraybossattack[1];

					$raineffect2 = mt_rand(0,99);
					if(($weather == "rainy") and ($raineffect2 < 20)) {
						$damage = ($damage * .8);
						$story = $story."The rain reduces the ".$boss."'s attack.,"; //story
					}

					$query4 = "SELECT * FROM players WHERE playerid = '$target' ";
						$recordset = mysql_query($query4) or die (mysql_error());
						$row = mysql_fetch_array($recordset);
					$currenthealth = $row["health"];

				//---player dodges---//
					$move = $row["playermove"];
					if($move == "dodge") {
						$story = $story."@".$target."& dodges.,"; //story
						$damage = ($damage - dodge($target));

						if($damage < 0) {
							$damage = 0;
							$story = $story."@".$target."& takes no damage.,"; //story
						}
						else {
							$story = $story."@".$taget."& takes ".$damage." damage from the ".$boss.".,"; //story
						}
					}

				//---player takes damage---//
					$newhealth = ($currenthealth - $damage);
					$query5 = "UPDATE players SET health = ('$newhealth') WHERE playerid = '$target' ";
						mysql_query($query5) or die (mysql_error());
				}

			//---attack all players---//
				elseif ($bossmove == "attackall") {
					$arraybossattack = explode(";",boss_attack_all($arrayplayersid));
					$arraytargets = explode(",",$arraybossattack[0]);
					$damage = $arraybossattack[1];

					$raineffect2 = mt_rand(0,99);
					if(($weather == "rainy") and ($raineffect2 < 20)) {
						$damage = ($damage * .8);
						$story = $story."The rain reduces the ".$boss."'s attack.,"; //story
					}

					foreach ($arraytargets as $target) {
						$query4 = "SELECT * FROM players WHERE playerid = '$target' ";
							$recordset = mysql_query($query4) or die (mysql_error());
							$row = mysql_fetch_array($recordset);
						$currenthealth = $row["health"];

					//---player dodges---//
						$move = $row["playermove"];
						if($move == "dodge") {
							$damage = ($damage - dodge($target));

							if($damage < 0) {
								$damage = 0;
								$story = $story."@".$target."& takes no damage.,"; //story
							}
							else {
								$story = $story."@".$target."& takes ".$damage." damage from the ".$boss.".,"; //story
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
					rest($playerid);
					$story = $story."@".$playerid."& rests to recover health and strength and speed.,"; //story
				}
			}

		//---change weather---//
			$newweather = change_weather($weather);

			$story = $story."The weather is ".$newweather."."; //story

			$query7 = "UPDATE games SET weather = ('$newweather') WHERE gameid = '$gameid' ";
				mysql_query($query7) or die (mysql_error());

		//---replace id numbers with names in story---//
			if (strpos($story, "@") !== FALSE) {
				$input = $story;
				preg_match_all("~@(.*?)&~", $input, $output);
				$arraytagged = $output[1];
									
				foreach($arraytagged as $taggedid) {
					$query8 = "SELECT * FROM players WHERE playerid = '$taggedid'";
					$recordset = mysql_query($query8) or die (mysql_error());
					$row = mysql_fetch_array($recordset);
					$name = $row["name"];

					if(isset($name)) {
						$story = str_replace("$taggedid", "$name", $story);
					}
				}
			}

		//---reset gamestate---//
			foreach ($arrayplayersid as $playerid) {
				$query9 = "UPDATE players SET playermove = ('') WHERE playerid = '$playerid' ";
					mysql_query($query9) or die (mysql_error());

				$query10 = "UPDATE players SET playerstate = ('ready') WHERE playerid = '$playerid' ";
					mysql_query($query10) or die (mysql_error());
			}

			$query11 = "UPDATE games SET bossmove = ('$story') WHERE gameid = '$gameid' ";
				mysql_query($query11) or die (mysql_error());

			$roundcount = $roundcount + 1;
			$query12 = "UPDATE games SET roundcount = ('$roundcount') WHERE gameid = '$gameid' ";
				mysql_query($query12) or die (mysql_error());

			$query13 = "UPDATE games SET gamestate = ('ready') WHERE gameid = '$gameid' ";
				mysql_query($query13) or die (mysql_error());

			return "complete";
		}
	}

?>