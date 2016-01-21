<?php

	include("bossmove.php");
	include("attack.php");
	include("bossattackone.php");
	include("bossattackall.php");
	include("dodge.php");
	include("rest.php");
	include("heal.php");
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
				if(($move == "attack") and ($weather == "rainy") and ($raineffect < number("percentage_rainy_miss"))) {
					$story = $story."Rain prevents @".$playerid."&'s attack.,"; //story
				}
				elseif(($move == "attack") and ($weather == "snowy") and ($snoweffect < number("percentage_snowy_miss"))) {
					$story = $story."Snow prevents @".$playerid."&'s attack.,"; //story
				}
				elseif($move == "attack") {
					$attack = attack($playerid);

					$windeffect = mt_rand(0,99);
					if (($weather == "windy") and ($windeffect < number("percentage_windy_reduce"))) {
						$attack = ($attack * number("windy_multiplier"));
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
				$totalattack = ($totalattack * number("boss_dodge_multiplier"));
				$story = $story."The ".$boss." dodges to escape some damage.,"; //story
			}

		//---boss takes damage---//
			$story = $story."The ".$boss." takes ".$totalattack." damage.,"; //story

			$query4 = "SELECT * FROM games WHERE gameid = '$gameid' ";
				$recordset = mysql_query($query4) or die (mysql_error());
				$row = mysql_fetch_array($recordset);

			$bosshealth = $row["bosshealth"];
			$newbosshealth = ($bosshealth - $totalattack);

			$query5 = "UPDATE games SET bosshealth = ('$newbosshealth') WHERE gameid = '$gameid' ";
				mysql_query($query5) or die (mysql_error());

		//---boss deals damage---//
			if ($newbosshealth > 0) {
				$windeffect2 = mt_rand(0,99);
				$snoweffect2 = mt_rand(0,99);

			//---weather: wind fail---//
				if((($bossmove == "attackone") or ($bossmove == "attackall")) and ($weather == "windy") and ($windeffect2 < number("percentage_windy_miss"))) {
					$story = $story."The wind causes the ".$boss."'s attack to miss.,"; //story
				}
				elseif((($bossmove == "attackone") or ($bossmove == "attackall")) and ($weather == "snowy") and ($snoweffect2 < number("percentage_snowy_miss"))) {
					$story = $story."The snow causes the ".$boss."'s attack to miss.,"; //story
				}

			//---attack one player---//
				elseif ($bossmove == "attackone") {
					$arraybossattack = explode(";",boss_attack_one($arrayplayersid));
					$target = $arraybossattack[0];
					$damage = $arraybossattack[1];

					$raineffect2 = mt_rand(0,99);
					if(($weather == "rainy") and ($raineffect2 < number("percentage_rainy_reduce"))) {
						$damage = ($damage * number("rainy_multiplier"));
						$story = $story."The rain reduces the ".$boss."'s attack.,"; //story
					}

					$query6 = "SELECT * FROM players WHERE playerid = '$target' ";
						$recordset = mysql_query($query6) or die (mysql_error());
						$row = mysql_fetch_array($recordset);
					$currenthealth = $row["health"];

				//---player dodges---//
					$move = $row["playermove"];
					if($move == "dodge") {
						$story = $story."@".$target."& dodges.,"; //story
						$damage = ($damage - dodge($target));

						if($damage < 0) {
							$damage = 0;
							$story = $story."@".$target."& dodges the ".$boss."'s attack and takes no damage.,"; //story
						}
						else {
							$story = $story."@".$target."& dodges the ".$boss."'s attack but takes ".$damage." damage.,"; //story
						}
					}
					else {
						$story = $story."The ".$boss." focuses an attack on @".$target."& for ".$damage." damage.,"; //story
					}

				//---player takes damage---//
					$newhealth = ($currenthealth - $damage);
					$query7 = "UPDATE players SET health = ('$newhealth') WHERE playerid = '$target' ";
						mysql_query($query7) or die (mysql_error());
				}

			//---attack all players---//
				elseif ($bossmove == "attackall") {
					$story = $story."The ".$boss." attacks everyone.,"; //story
					$arraybossattack = explode(";",boss_attack_all($arrayplayersid));
					$arraytargets = explode(",",$arraybossattack[0]);
					$damage = $arraybossattack[1];

					$raineffect2 = mt_rand(0,99);
					if(($weather == "rainy") and ($raineffect2 < number("percentage_rainy_miss"))) {
						$damage = ($damage * number("rainy_multiplier"));
						$story = $story."The rain reduces the ".$boss."'s attack.,"; //story
					}

					foreach ($arraytargets as $target) {
						$query8 = "SELECT * FROM players WHERE playerid = '$target' ";
							$recordset = mysql_query($query8) or die (mysql_error());
							$row = mysql_fetch_array($recordset);
						$currenthealth = $row["health"];

					//---player dodges---//
						$move = $row["playermove"];
						if($move == "dodge") {
							$damage = ($damage - dodge($target));

							if($damage < 0) {
								$damage = 0;
								$story = $story."@".$target."& dodges the ".$boss."'s attack and takes no damage.,"; //story
							}
							else {
								$story = $story."@".$target."& dodges the ".$boss."'s attack but takes ".$damage." damage.,"; //story
							}
						}
						else {
							$story = $story."@".$target."& takes ".$damage." damage from the ".$boss." attack.,"; //story
						}

					//---player takes damage---//
						$newhealth = ($currenthealth - $damage);
						$query9 = "UPDATE players SET health = ('$newhealth') WHERE playerid = '$target' ";
							mysql_query($query9) or die (mysql_error());
					}
				}
			}

		//---boss dies---//
			else {
				$story = $story."The ".$boss." is defeated!,";
				$endgame = "victory";
			}

		//---players rest---//
			foreach ($arrayplayersid as $playerid) {
				$query10 = "SELECT * FROM players WHERE playerid = '$playerid' ";
					$recordset = mysql_query($query10) or die (mysql_error());
					$row = mysql_fetch_array($recordset);
				$move = $row["playermove"];

				if($move == "rest") {
					rest($playerid);
					$story = $story."@".$playerid."& rests to recover health and strength and speed.,"; //story
				}
			}

		//---players heal---// - not currently active, but would replace rest to add to the co-op
			foreach ($arrayplayersid as $playerid) {
				$query10 = "SELECT * FROM players WHERE playerid = '$playerid' ";
					$recordset = mysql_query($query10) or die (mysql_error());
					$row = mysql_fetch_array($recordset);
				$move = $row["playermove"];

				if($move == "heal") {
					heal($playerid);
					$story = $story."@".$playerid."& heals the team to help them recover.,"; //story
				}
			}

		//---players die---//
			$arraydead = array("");
				array_pop($arraydead);

			foreach($arrayplayersid as $playerid) {
				$query11 = "SELECT * FROM players WHERE playerid = '$playerid' ";
				$recordset = mysql_query($query11) or die (mysql_error());
				$row = mysql_fetch_array($recordset);
				$health = $row["health"];

				if(($health == 0) or ($health < 0)) {
					$deadplayer = array($playerid);
					$arraydead = array_merge($arraydead, $deadplayer);

					if($row["playerstate"] !== "dead") {
						$story = $story."@".$playerid."& has died.,"; //story
						$query12 = "UPDATE players SET health = '0' WHERE playerid = '$playerid' ";
							mysql_query($query12) or die (mysql_error());
					}
				}
			}

		//---change weather---//
			$newweather = change_weather($weather);
			$story = $story."The weather is ".$newweather."."; //story

			$query13 = "UPDATE games SET weather = ('$newweather') WHERE gameid = '$gameid' ";
				mysql_query($query13) or die (mysql_error());

		//---replace id numbers with names in story---//
			if (strpos($story, "@") !== FALSE) {
				$input = $story;
				preg_match_all("~@(.*?)&~", $input, $output);
				$arraytagged = $output[1];
									
				foreach($arraytagged as $taggedid) {
					$query14 = "SELECT * FROM players WHERE playerid = '$taggedid'";
					$recordset = mysql_query($query14) or die (mysql_error());
					$row = mysql_fetch_array($recordset);
					$name = $row["name"];

					if(isset($name)) {
						$story = str_replace("@$taggedid&", "$name", $story);
					}
				}
			}

		//---reset gamestate---//
			foreach ($arrayplayersid as $playerid) {
				$query15 = "UPDATE players SET playermove = ('') WHERE playerid = '$playerid' ";
					mysql_query($query15) or die (mysql_error());

				$query16 = "UPDATE players SET playerstate = ('playerturn') WHERE playerid = '$playerid' ";
					mysql_query($query16) or die (mysql_error());
			}

			foreach ($arraydead as $playerid) {
				$query17 = "UPDATE players SET playerstate = ('dead') WHERE playerid = '$playerid' ";
					mysql_query($query17) or die (mysql_error());
			}

			$slashstory = addslashes($story);
			$query18 = "UPDATE games SET bossmove = ('$slashstory') WHERE gameid = '$gameid' ";
				mysql_query($query18) or die (mysql_error());

			$roundcount = $roundcount + 1;
			$query19 = "UPDATE games SET roundcount = ('$roundcount') WHERE gameid = '$gameid' ";
				mysql_query($query19) or die (mysql_error());

		//---game over?---//
			if(isset($endgame) and ($endgame == "victory")) {
				$query20 = "UPDATE games SET gamestate = ('victory') WHERE gameid = '$gameid' ";
					mysql_query($query20) or die (mysql_error());

				foreach ($arrayplayersid as $playerid) {
				$query21 = "UPDATE players SET playerstate = ('victory') WHERE playerid = '$playerid' ";
					mysql_query($query21) or die (mysql_error());
				}

				return "victory";
			}
			elseif($arrayplayersid == $arraydead) {
				$query22 = "UPDATE games SET gamestate = ('defeat') WHERE gameid = '$gameid' ";
					mysql_query($query22) or die (mysql_error());

				return "defeat";
			}
			else {
				$query23 = "UPDATE games SET gamestate = ('playerturn') WHERE gameid = '$gameid' ";
					mysql_query($query23) or die (mysql_error());
				return "continue";
			}
		}
	}

?>