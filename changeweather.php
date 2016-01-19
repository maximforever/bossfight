<?php

	function change_weather($weather) {

		$randomchange = mt_rand(0,99);

		if ($randomchange < 50) {
			$newweather = $weather;
		}
		else {
			$randomweather = mt_rand(0,99);

			if ($randomweather < 30) {
				$newweather = "sunny";
			}
			elseif (($randomweather > 29) and ($randomweather < 60)) {
				$newweather = "windy";
			}
			elseif (($randomweather > 59) and ($randomweather < 90)) {
				$newweather = "rainy";
			}
			elseif ($randomweather > 89) {
				$newweather = "snowy";
			}
		}

		return $newweather;
	}

?>