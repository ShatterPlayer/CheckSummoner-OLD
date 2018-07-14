<?php
	session_start();
	
	//error_reporting(E_STRICT);
	
	if(!isset($_POST['nick']))
	{
		header('Location: index.php#error');
		exit();
	}
	
	if(!ctype_alnum($_POST['nick']))
	{
		$_SESSION['select'] = $_POST['region'];
		$_SESSION['error'] = "Podany nick jest nieprawidłowy!";
		header("Location: index.php#error"); exit();
	}
	
	$secret_keys = require_once "secrets.php";
	/*
		$responseKey = $_POST['g-recaptcha-response'];
		$ip = $_SERVER['REMOTE_ADDR'];
		$url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret_keys[1]&response=$responseKey&remoteip=$ip";
		$check = file_get_contents($url);
		$answear = json_decode($check);
		
		if($answear->success==false)
		{
		$_SESSION['nick'] = $_POST['nick'];
		$_SESSION['select'] = $_POST['region'];
		$_SESSION['error'] = "Udowodnij, że nie jesteś robotem!";
		header("Location: index.php#error"); exit();
		}
	*/
	
	$nick = $_POST['nick'];
	$region = $_POST['region'];
	
	$API_KEY=$secret_keys[0];
	require_once "apiconnect.php";
	$player = apiRequest('https://'.$region.".api.riotgames.com"."/lol/summoner/v3/summoners/by-name/{$nick}");
	
	if(isset($player->status->status_code))
	{
		if($player->status->status_code == 404)
		{	
			$_SESSION['select'] = $_POST['region'];
			$_SESSION['error'] = "Gracz o takim nicku nie istnieje!";
			header('Location: index.php#error'); exit();
		}
		if($player->status->status_code == 429)
		{
			$_SESSION['nick'] = $nick;
			$_SESSION['select'] = $_POST['region'];
			$_SESSION['error'] = "Strona jest przeciążona. Spróbuj ponownie za chwilę!";
			header('Location: index.php#error'); exit();
		}
		else
		{	
			$_SESSION['nick'] = $nick;
			$_SESSION['select'] = $_POST['region'];
			$_SESSION['error'] = "Wystąpił błąd. Spróbuj ponownie później! (kod: ".$player->status->status_code.")";
			header('Location: index.php#error'); exit();
		}
	}
	
	$champions = require_once 'champions.php';
	$versions = apiRequest('https://ddragon.leagueoflegends.com/api/versions.json');
	
	$mastery = apiRequest('https://'.$region.".api.riotgames.com"."/lol/champion-mastery/v3/champion-masteries/by-summoner/{$player->id}");
	
	function exist($v)
	{
		if(isset($v)) return $v;
		else return '?';
	}
	
	function tstamp_to_date($t)
	{
		return date('d-m-Y H:i:s', (int) round($t/1000, 0));;
	}
	
	$matchesId = apiRequest('https://'.$region.".api.riotgames.com"."/lol/match/v3/matchlists/by-account/{$player->accountId}?endIndex=5");
	print_r($matchesId);
	
	
?>

<!DOCTYPE html>
<html>
	<head>
		
		<title><?=exist($player->name)?> - Statystyki Gracza</title>
		<meta name="description" content="Sprawdź statystyki graczy League of Legends z całego świata - szybko i bezproblemowo">
		<meta name="keywords" content="league, legends, lol, statystyki, mecze, summoner">
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<link rel="stylesheet" href="stats.css">
		<link href="https://fonts.googleapis.com/css?family=Lato:400,900&amp;subset=latin-ext" rel="stylesheet">
		<script src="imgerror.js" type="javascript"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		
		<link rel="stylesheet" href="spinner.css">
		<link rel="stylesheet" type="text/css" href="preloader/preloader.css">
		<script src="preloader/preloader.js"></script>
		
		<!--[if lt IE 9]>
			<script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
		<![endif]-->
		
	</head>
	<body>
		
		<!-- PRELOADER -->
		<div class="preloader-wrapper">
			<div class="preloader">
				<div class="loader"><div class="sk-folding-cube"><div class="sk-cube1 sk-cube"></div><div class="sk-cube2 sk-cube"></div><div class="sk-cube4 sk-cube"></div><div class="sk-cube3 sk-cube"></div></div></div>
				</div>
		</div>
		<!--------------------------->
		
		<header>
		
			<div class="logo">
				<a href="index.php"><img src="img/banner.png"></a>
			</div>
			
		</header>
		<main>
			<div class="container">
				
				<div class="playerinfo">
					
					<img class="floatleft" 
					src="http://ddragon.leagueoflegends.com/cdn/<?=$versions[0]?>/img/profileicon/<?=$player->profileIconId?>.png" onerror="this.src='img/noimg.jpg';" width="100">
					<h2 class="floatleft"><?=exist($player->name)?></h2>
					<h3><span class="red">LVL </span><span class="blue"><?=exist($player->summonerLevel)?></span></h3>
					<div style="clear: both;"></div>
					
				</div>
				<div class="mastery">
					<h2><span class="blue">Maestrie </span><span class="red">Championów</span></h2>
					<figure class="place1">
						<figcaption class="place">I</figcaption>
						<!--OBRAZEK-->
						<img src="http://ddragon.leagueoflegends.com/cdn/<?=$versions[0]?>/img/champion/<?=$champions[$mastery[0]->championId]?>.png" onerror="this.src='img/noimg.jpg';">
						<!--NAKŁADKA Z PUNKTAMI-->
						<div class="overlay">
							<div class="oltext"><span class="blue"><?=exist($mastery[0]->championPoints)?></span><br>
							<span class="red">PKT</span></div></div>
							<!--LVL-->
							<figcaption class="lvl">LVL <?=exist($mastery[0]->championLevel)?></figcaption>
							
					</figure>
					
					<figure class="place2">
						<figcaption class="place">II</figcaption>
						<!--OBRAZEK-->
						<img src="http://ddragon.leagueoflegends.com/cdn/<?=$versions[0]?>/img/champion/<?=$champions[$mastery[1]->championId]?>.png" onerror="this.src='img/noimg.jpg';">
						<!--NAKŁADKA Z PUNKTAMI-->
						<div class="overlay">
						<div class="oltext"><span class="blue"><?=exist($mastery[1]->championPoints)?></span><br><span class="red">PKT</span></div></div>
						<!--LVL-->
						<figcaption class="lvl">LVL <?=exist($mastery[1]->championLevel)?></figcaption>
					</figure>
					
					<figure class="place3">
						<figcaption class="place">III</figcaption>
						<!--OBRAZEK-->
						<img src="http://ddragon.leagueoflegends.com/cdn/<?=$versions[0]?>/img/champion/<?=$champions[$mastery[2]->championId]?>.png" onerror="this.src='img/noimg.jpg';">
						<!--NAKŁADKA Z PUNKTAMI-->
						<div class="overlay">
						<div class="oltext"><span class="blue"><?=exist($mastery[2]->championPoints)?></span><br><span class="red">PKT</span></div></div>
						<!--LVL-->
						<figcaption class="lvl">LVL <?=exist($mastery[2]->championLevel)?></figcaption>
					</figure>
					<div style="clear: both;"></div>
				</div>
				
				<div class="matches">
					<h2><span class="blue">Ostatnie</span> <span class="red">Mecze</span></h2>
					<?php  
						if(isset($matchesId->status))
						echo '<h3>Nie mogę wyświetlić ostatnich meczy :(</h3>';
						else
						{
							foreach($matchesId->matches as $m)
							{
								echo '
								<div class="match">
								<img src="http://ddragon.leagueoflegends.com/cdn/'.$versions[0].'/img/champion/'.$champions[$m->champion].'.png" onerror="this.src=\'img/noimg.jpg\';">
								<div class=matchtext">
								<h2>'.$m->lane.'</h2>
								</div>
								<div style="clear: both;"></div>
								</div>
								';
							}
						}
					?>
					
				</div>
				
			</div>
		</main>
		
		<footer>
			<div class="footer">
				<span class="blue">Check</span><span class="red">Summoner</span> &copy; - Statystyki graczy League of Legends<br>
				Kontakt: shatterplayer@gmail.com
			</div>
		</footer>
		
	</body>	
</html>