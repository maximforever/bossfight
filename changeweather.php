<?php

	function change_weather($weather) {

		$randomchange = mt_rand(0,99);

		if ($randomchange > number("percentage_weather_change")) {
			$newweather = $weather;
		}
		else {
			$randomweather = mt_rand(0,99);

			if ($randomweather < number("percentage_weather_sunny")) {
				$newweather = "sunny";
			}
			elseif (($randomweather > (number("percentage_weather_sunny") - 1)) and ($randomweather < (number("percentage_weather_sunny") + number("percentage_weather_windy")))) {
				$newweather = "windy";
			}
			elseif (($randomweather > (number("percentage_weather_sunny") + number("percentage_weather_windy") -1)) and ($randomweather < (number("percentage_weather_sunny") + number("percentage_weather_windy") + number("percentage_weather_rainy")))) {
				$newweather = "rainy";
			}
			elseif ($randomweather > (99 - number("percentage_weather_snowy"))) {
				$newweather = "snowy";
			}
		}

		return $newweather;
	}

?>