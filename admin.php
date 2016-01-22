<?php
	
	include("config.php");

//---test database---//
	if(isset($_POST["update_database"])) {
		include("createdatabase.php");

		echo "<br>database is up to date.";
	}

//---test new game---//
	elseif(isset($_POST["new_game"])) {
		include("newgame.php");
		$gameid = new_game();
		echo "new game created: ".$gameid;
	}

//---test new player---//
	elseif(isset($_POST["new_player"])) {
		if(isset($_POST["text_input"]) and ($_POST["text_input"] > 0)) {
			include("newplayer.php");
			$name = "test_player";
			$gameid = $_POST["text_input"];
			$browserinfo = $_SERVER['HTTP_USER_AGENT'];
			$playerid = new_player($name,$gameid,$browserinfo);
			$_SESSION["playerid"] = $playerid;
			echo "game: ".$gameid."<br>new player created: ".$playerid;
		}
		else {
			echo "enter gameid in text field";
		}
	}

//---test starting the game---//
	elseif(isset($_POST["start_game"])) {
		if(isset($_POST["text_input"]) and ($_POST["text_input"] > 0)) {
			$_GET["game"] = $_POST["text_input"];
			include("startgame.php");
			$gameid = start_game($gameid);
			echo "game ".$gameid." started";
		}
		elseif(isset($_SESSION["playerid"])) {
			include("startgame.php");
			$gameid = start_game($gameid);
			echo "game ".$gameid." started";
		}
		else {
			echo "create a player first";
		}
	}

//---test getting status---//
	elseif(isset($_POST["get_status"])) {
		if(isset($_POST["text_input"]) and ($_POST["text_input"] > 0)) {
			if(isset($_SESSION["playerid"])) {
				$amnesia = $_SESSION["playerid"];
				unset($_SESSION["playerid"]);
			}

			$_POST["game"] = $_POST["text_input"];
			echo "<b>gameid; boss; bosshealth; weather; gamestate; roundcount; story; </b>6 zeros for player stats<b>; all players</b><br>";
			include("status.php");
			$_SESSION["playerid"] = $amnesia;
		}
		elseif(isset($_SESSION["playerid"])) {
			echo "<b>gameid; boss; bosshealth; weather; gamestate; roundcount; story; name; health; strength; speed; playermove; playerstate; other players</b><br>";
			include("status.php");
		}
		else {
			echo "create a game first";
		}
	}

//---test submitting move---//
	elseif(isset($_POST["submit_move"])) {
		if(!isset($_SESSION["playerid"])) {
			echo "create a player first";
		}
		elseif(isset($_POST["text_input"])) {
			if($_POST["text_input"] == "attack") {
				$_POST["playermove"] = "attack";
				include("playermove.php");
			}
			elseif($_POST["text_input"] == "dodge") {
				$_POST["playermove"] = "dodge";
				include("playermove.php");
			}
			elseif($_POST["text_input"] == "heal") {
				$_POST["playermove"] = "heal";
				include("playermove.php");
			}
			else {
				echo "enter 'attack' or 'dodge' or 'heal'";
			}
		}
		else {
			echo "enter 'attack' or 'dodge' or 'heal'";
		}
	}

//---test quitting game---//
	elseif(isset($_POST["quit_game"])) {
		if(isset($_SESSION["playerid"])) {
			include("startgame.php");
			$_SESSION["playerid"] = quit_game($_SESSION["playerid"]);
			echo $_SESSION["playerid"]." quit.";
			unset($_SESSION["playerid"]);
		}
		else {
			echo "no active game to quit.";
		}
	}

?>

<html>
	<head>
		<link rel = "stylesheet" type = "html/css" href ="assets/bootstrap.min.css">
		<link href='https://fonts.googleapis.com/css?family=Fondamento:400,400italic' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Antic+Didone' rel='stylesheet' type='text/css'>
		<link rel = "stylesheet" type = "html/css" href ="style.css">
	</head>
	<body>
		<hr>
		<div style="text-align: center; margin: -10px">
			<h1>^ test results ^</h1>
		</div>
		<hr>
		<form action="admin.php" method="post" style="text-align: center; margin-left: 30px; margin-right: 30px">
			<input class="form-control" type="text" name="text_input" placeholder="text input" style="margin-bottom: 10px"></input>
			<div class="btn-group">
				<button class="btn btn-info btn-lg" name="update_database" type="submit">Update Database</button>
				<button class="btn btn-success btn-lg" name="new_game" type="submit">New Game</button>
				<button class="btn btn-success btn-lg" name="new_player" type="submit">New Player</button>
				<button class="btn btn-success btn-lg" name="start_game" type="submit">Start Game</button>
				<button class="btn btn-warning btn-lg" name="get_status" type="submit">Get Status</button>
				<button class="btn btn-warning btn-lg" name="submit_move" type="submit">Submit Move</button>
				<button class="btn btn-danger btn-lg" name="quit_game" type="submit">Quit Game</button>
			</div>
		</form>
		
	</body>
</html>