<?php

//---create database---//
	mysql_connect("localhost", "root", "") or die (mysql_error());
	mysql_query("CREATE DATABASE IF NOT EXISTS bossfightdb DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci") or die (mysql_error());
	mysql_select_db("bossfightdb") or die (mysql_error());

//---create games table---//
	mysql_query("CREATE TABLE IF NOT EXISTS games (
		gameid INT(10) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
		players VARCHAR(255) NOT NULL,
		boss VARCHAR(255) NOT NULL,
		bosshealth INT(4) UNSIGNED ZEROFILL NOT NULL,
		weather VARCHAR(255) NOT NULL,
		bossmove VARCHAR(255) NOT NULL,
		gamestate VARCHAR(255) NOT NULL,
		roundcount INT(4) UNSIGNED ZEROFILL NOT NULL,
		starttime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY(gameid)
	)") or die (mysql_error());

//---create players table---//
	mysql_query("CREATE TABLE IF NOT EXISTS players (
		playerid INT(10) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
		name VARCHAR(255) NOT NULL,
		gameid INT(10) UNSIGNED ZEROFILL NOT NULL,
		health INT(4) UNSIGNED ZEROFILL NOT NULL,
		strength INT(4) UNSIGNED ZEROFILL NOT NULL,
		speed INT(4) UNSIGNED ZEROFILL NOT NULL,
		playermove VARCHAR(255) NOT NULL,
		playerstate VARCHAR(255) NOT NULL,
		browserinfo TEXT NOT NULL,
		PRIMARY KEY(playerid)
	)") or die (mysql_error());

?>