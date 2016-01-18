<?php

	function boss_attack_all($arrayplayersid);
		$target = "";

		foreach($arrayplayersid as $playerid) {
			$target = $target.$playerid.",";
		}

		$attack = 4;

		return $target.";".$attack;
	}
	
?>