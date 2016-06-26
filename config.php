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

?>