<?php

//---create database---//
	mysql_connect("localhost", "root", "") or die (mysql_error());
	mysql_query("CREATE DATABASE IF NOT EXISTS bossfightdb DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci") or die (mysql_error());
	mysql_select_db("bossfightdb") or die (mysql_error());

//---create games table---//
	mysql_query("CREATE TABLE IF NOT EXISTS games (
		gameid INT(10) NOT NULL AUTO_INCREMENT,
		gamecode VARCHAR(16) NOT NULL,
		players VARCHAR(255) NOT NULL,
		boss VARCHAR(255) NOT NULL,
		bosshealth INT(4) NOT NULL,
		weather VARCHAR(255) NOT NULL,
		bossmove TEXT NOT NULL,
		gamestate VARCHAR(255) NOT NULL,
		roundcount INT(4) NOT NULL,
		starttime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY(gameid)
	)") or die (mysql_error());

//---create players table---//
	mysql_query("CREATE TABLE IF NOT EXISTS players (
		playerid INT(10) NOT NULL AUTO_INCREMENT,
		name VARCHAR(255) NOT NULL,
		gameid INT(10) NOT NULL,
		health INT(4) NOT NULL,
		strength INT(4) NOT NULL,
		speed INT(4) NOT NULL,
		playermove VARCHAR(255) NOT NULL,
		playerstate VARCHAR(255) NOT NULL,
		browserinfo TEXT NOT NULL,
		PRIMARY KEY(playerid)
	)") or die (mysql_error());

//---describe tables---//
		$description = "<b>games</b><br>";
		$query1 = mysql_query('DESCRIBE games');
		while($row = mysql_fetch_array($query1)) {
			$description = $description."{$row['Field']} - {$row['Type']}<br>";
		}

		$description = $description."<br><b>players</b><br>";
		$query2 = mysql_query('DESCRIBE players');
		while($row = mysql_fetch_array($query2)) {
			$description = $description."{$row['Field']} - {$row['Type']}<br>";
		}

//---add roundcount, remove zerofills 01/20/16---//
		if(strpos($description,"gamecode") == FALSE) {
			mysql_query("ALTER TABLE `games`
				ADD gamecode VARCHAR(16) NOT NULL AFTER gameid
			") or die (mysql_error());

			mysql_query("ALTER TABLE `games`
				CHANGE `bossmove` `bossmove` TEXT NOT NULL
			") or die (mysql_error());

			$description = "<b>games</b><br>";
			$query1 = mysql_query('DESCRIBE games');
			while($row = mysql_fetch_array($query1)) {
				$description = $description."{$row['Field']} - {$row['Type']}<br>";
			}

			$description = $description."<br><b>players</b><br>";
			$query2 = mysql_query('DESCRIBE players');
			while($row = mysql_fetch_array($query2)) {
				$description = $description."{$row['Field']} - {$row['Type']}<br>";
			}
		}	

		echo $description;

?>
