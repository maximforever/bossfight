<?php

	//---starting database---//
		$dbhost = 'localhost';
		$dbuser = 'root';
		$dbpass = '';
			mysql_connect($dbhost, $dbuser, $dbpass) or die (mysql_error());

		$dbname = 'bossfightdb';
			mysql_select_db($dbname);

	//---starting session---//
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}

	//---setting parameters---//
		if(!function_exists('number')) {
			function number ($n) {
				if ($n == "") {
					return 0;
				}

			//starting
				elseif ($n == "starting_health") {
					return 100;			/* CHANGED to 100 for testing, should be 20*/
				}	
				elseif ($n == "starting_strength") {
					return 10;
				}
				elseif ($n == "starting_speed") {
					return 10;
				}
				elseif ($n == "starting_boss_health_per_player") {
					return 50;
				}

			//boss moves
				elseif ($n == "percentage_boss_attack_one") {
					return 30;
				}
				elseif ($n == "percentage_boss_attack_all") {
					return 50;
				}
				elseif ($n == "percentage_boss_dodge") {
					return 20;
				}
				elseif ($n == "boss_attack_one_min") {
					return 2;
				}
				elseif ($n == "boss_attack_one_max") {
					return 10;
				}
				elseif ($n == "boss_attack_all_min") {
					return 1;
				}
				elseif ($n == "boss_attack_all_max") {
					return 5;
				}
				elseif ($n == "boss_dodge_multiplier") {
					return .5;
				}

			//weather change
				elseif ($n == "percentage_weather_change") {
					return 50;
				}
				elseif ($n == "percentage_weather_sunny") {
					return 30;
				}
				elseif ($n == "percentage_weather_windy") {
					return 30;
				}
				elseif ($n == "percentage_weather_rainy") {
					return 30;
				}
				elseif ($n == "percentage_weather_snowy") {
					return 10;
				}

			//weather effects
				elseif ($n == "percentage_rainy_miss") {
					return 5;
				}
				elseif ($n == "percentage_windy_miss") {
					return 5;
				}
				elseif ($n == "percentage_snowy_miss") {
					return 20;
				}
				elseif ($n == "percentage_rainy_reduce") {
					return 20;
				}
				elseif ($n == "rainy_multiplier") {
					return .8;
				}
				elseif ($n == "percentage_windy_reduce") {
					return 20;
				}
				elseif ($n == "windy_multiplier") {
					return .8;
				}

			//heal
				elseif ($n == "heal_health_min") {
					return 10;
				}
				elseif ($n == "heal_health_max") {
					return 30;
				}
				elseif ($n == "heal_strength_min") {
					return 3;
				}
				elseif ($n == "heal_strength_max") {
					return 6;
				}
				elseif ($n == "heal_speed_min") {
					return 3;
				}
				elseif ($n == "heal_speed_max") {
					return 6;
				}

			}
		}

		if(!function_exists('storytime')) {
			function storytime ($move) {
				$random = mt_rand(0,99);
				
				if ($move == "dodge") {
					if (($random >= 0) && ($random < 20)) {
						return " jumps out of the way just as the flame strikes the tips of his/her mustache.";
					}
					if (($random >= 20) && ($random < 40)) {
						return " walks two steps to the left and grins.";
					}
					if (($random >= 40) && ($random < 60)) {
						return " bends down to pick up a shiny coin and the fire scorches the top of his/her hair.";
					}
					if (($random >= 60) && ($random < 80)) {
						return "... something something Matrix reference.";
					}
					if (($random >= 80) && ($random < 100)) {
						return " can't even get hit right.";
					}
				}

				elseif ($move == "attack") {
					if (($random >= 0) && ($random < 20)) {
						return " is just showing off now.";
					}
					if (($random >= 20) && ($random < 40)) {
						return " accidentally charges ahead like a madman on bath salts.";
					}
					if (($random >= 40) && ($random < 60)) {
						return " : 'LEEEEEROYYYYYYY JENKINS!!!1!!1!'";
					}
					if (($random >= 60) && ($random < 80)) {
						return " has somehow managed to actually deal some damage.";
					}
					if (($random >= 80) && ($random < 100)) {
						return " aims true and strikes right between the eyes.";
					}
				}

				elseif ($move == "heal") {
					if (($random >= 0) && ($random < 20)) {
						return " summons the power of the gods.";
					}
					if (($random >= 20) && ($random < 40)) {
						return " is a certified physician.";
					}
					if (($random >= 40) && ($random < 60)) {
						return " does a little dance and sings a little song.";
					}
					if (($random >= 60) && ($random < 80)) {
						return " is using those college CPR classes to a great extent.";
					}
					if (($random >= 80) && ($random < 100)) {
						return " masterfully cures some wounds.";
					}
				}

				elseif ($move == "boss") {
					if (($random >= 0) && ($random < 10)) {
						return " snarls and growls and howls and strikes.";
					}
					if (($random >= 10) && ($random < 20)) {
						return " swoops down from above to deal a damaging blow.";
					}
					if (($random >= 20) && ($random < 30)) {
						return " spins around and strikes everyone in its path.";
					}
					if (($random >= 30) && ($random < 40)) {
						return " locks on target.";
					}
					if (($random >= 40) && ($random < 50)) {
						return " bashes into the heroes.";
					}
					if (($random >= 50) && ($random < 60)) {
						return " huffs and puffs and blows the heroes down.";
					}
					if (($random >= 60) && ($random < 70)) {
						return " rampages around the dungeon.";
					}
					if (($random >= 70) && ($random < 80)) {
						return " trips and falls into the heroes like a bunch of bowling pins.";
					}
					if (($random >= 80) && ($random < 90)) {
						return " actually does nothing but the hero pulls a muscle anyway.";
					}
					if (($random >= 90) && ($random < 100)) {
						return " finds your lack of faith disturbing.";
					}
				}

				elseif ($move == "die") {
					if (($random >= 0) && ($random < 20)) {
						return "... THIS WAS A TRIUMPH. I'M MAKING A NOTE HERE: HUGE SUCCESS.";
					}
					if (($random >= 20) && ($random < 40)) {
						return "... gg.";
					}
					if (($random >= 40) && ($random < 60)) {
						return "... GAME OVER.";
					}
					if (($random >= 60) && ($random < 80)) {
						return "... WASTED.";
					}
					if (($random >= 80) && ($random < 100)) {
						return "... Requiescat in pace.";
					}
				}
			}
		}

?>