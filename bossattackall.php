<?php

	function boss_attack_all($arrayplayersid) {
		$target = "";

		foreach($arrayplayersid as $playerid) {
			$target = $target.$playerid.",";
		}

		$target = substr($target,0,-1);

		$attack = mt_rand(1,5);

		return $target.";".$attack;
	}
	
?>