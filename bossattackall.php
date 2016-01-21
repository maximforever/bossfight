<?php

	function boss_attack_all($arrayplayersid) {
		$target = "";

		foreach($arrayplayersid as $playerid) {
			$target = $target.$playerid.",";
		}

		$target = substr($target,0,-1);

		$attack = mt_rand(number("boss_attack_one_min"),number("boss_attack_one_max"));

		return $target.";".$attack;
	}
	
?>