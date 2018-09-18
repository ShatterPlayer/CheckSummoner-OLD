<!DOCTYPE html>
<?php
	session_start();
	
	error_reporting(E_STRICT);
	
	if(!isset($_POST['nick']) && !isset($_POST['region']))
	{
		header('Location: index.php#error');
		exit();
	}

	require_once 'allowed_characters.php';

	if(!preg_match('/^['.$chars.']+$/i', $_POST['nick']))
	{
		$_SESSION['select'] = $_POST['region'];
		$_SESSION['error'] = "Podany nick jest nieprawidłowy!";
		header("Location: index.php#error"); exit();
	}

	$secret_keys = require_once "secrets.php";
	
	//Captcha, comment under code to disable
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
	//###########
	
	$API_KEY=$secret_keys[0];
	require_once "apiconnect.php";

	$nick = rawurlencode($_POST['nick']);
	$region = $_POST['region'];

	$player = apiRequest('https://'.$region.".api.riotgames.com"."/lol/summoner/v3/summoners/by-name/{$nick}");

	if(isset($player->status->status_code))
	{
		if($player->status->status_code == 404)
		{	
			$_SESSION['select'] = $_POST['region'];
			$_SESSION['error'] = "Gracz o takim nicku nie istnieje!";
			header('Location: index.php#error'); exit();
		}
		else if($player->status->status_code == 429)
		{
			$_SESSION['nick'] = $_POST['nick'];
			$_SESSION['select'] = $_POST['region'];
			$_SESSION['error'] = "Strona jest przeciążona. Spróbuj ponownie za chwilę!";
			header('Location: index.php#error'); exit();
		}
		else
		{	
			$_SESSION['nick'] = $_POST['nick'];
			$_SESSION['select'] = $_POST['region'];
			$_SESSION['error'] = "Wystąpił błąd. Spróbuj ponownie za chwilę! (kod: ".$player->status->status_code.")";
			header('Location: index.php#error'); exit();
		}
	}
	
	try
	{
		//$league = apiRequest('https://'.$region.".api.riotgames.com"."/lol/league/v3/positions/by-summoner/{$player->id}");
		//if(!isset($league)) throw new Exception();

		$mastery = apiRequest('https://'.$region.".api.riotgames.com"."/lol/champion-mastery/v3/champion-masteries/by-summoner/{$player->id}");
		if(!isset($mastery)) throw new Exception();
		
		$matchesId = apiRequest('https://'.$region.".api.riotgames.com"."/lol/match/v3/matchlists/by-account/{$player->accountId}?endIndex=10");
		if(!isset($matchesId)) throw new Exception();
	}
	catch(Exception $e)
	{
		$_SESSION['nick'] = $nick;
		$_SESSION['select'] = $_POST['region'];
		$_SESSION['error'] = "Wystąpił błąd. Spróbuj ponownie za chwilę! (kod: ".$e->getCode().")";
		header('Location: index.php#error'); exit();
	}

	function exist($v)
	{
		if(isset($v)) return $v;
		else return '?';
	}
	
	function tstamp_to_days_ago($timestamp)
	{
		$date1 = date('Y-m-d H:i:s', (int) round($timestamp/1000, 0));
		$date2 = date('Y-m-d H:i:s');
		$datetime1 = date_create($date1);
		$datetime2 = date_create($date2);
		$interval = date_diff($datetime1, $datetime2);
		
		//Days
		if($interval->format('%a') == 1)
			$days = '%a dzień';
		else
			$days = '%a dni';
		
		//Hours
		if($interval->format('%h') == 1)
			$hours = '%h godzinę';
		else if((($interval->format('%h') % 10) > 1 && ($interval->format('%h') % 10) < 5) && ($interval->format('%h') > 21 || $interval->format('%h') <5))
			$hours =  '%h godziny';
		else
				$hours = '%h godzin';
			
		//Return wariants
		if($interval->format('%a') == 0 && $interval->format('%h') == 0)
			return $interval->format('niecałą godzinę temu');
		
		else if($interval->format('%a') == 0)
			return $interval->format($hours.' temu');
		
		else if($interval->format('%h') == 0)
			return $interval->format($days.' temu');
		
		else
			return $interval->format($days.' i '.$hours.' temu');
	}
	
	$champions = require_once 'champions.php';

	$versions = apiRequest('https://ddragon.leagueoflegends.com/api/versions.json');
	
?>

<html lang="pl">
	<head>
		
		<!-- Clear Cache -->
		<meta http-equiv="Expires" content="0" />
		<meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate" />
		<meta http-equiv="Cache-Control" content="post-check=0, pre-check=0" />
		<meta http-equiv="Pragma" content="no-cache" />
	
		<meta name="viewport" content="width=device-width, initial-scale=1.0" >
		
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
		
		<!--Favicon-->
		<link rel="apple-touch-icon" sizes="57x57" href="img/favicon/apple-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="img/favicon/apple-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="img/favicon/apple-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="img/favicon/apple-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="img/favicon/apple-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="img/favicon/apple-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="img/favicon/apple-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="img/favicon/apple-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="img/favicon/apple-icon-180x180.png">
		<link rel="icon" type="image/png" sizes="192x192"  href="img/favicon/android-icon-192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="img/favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="img/favicon/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="img/favicon/favicon-16x16.png">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="img/favicon/ms-icon-144x144.png">
		<meta name="theme-color" content="#ffffff">
		<!-- -->
		
		<!--[if lt IE 9]>
			<script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
		<![endif]-->
		
	</head>
	<body onselectstart="return false">
		
		<!-- PRELOADER -->
		<div class="preloader-wrapper">
			<div class="preloader">
				<div class="loader"><div class="sk-folding-cube"><div class="sk-cube1 sk-cube"></div><div class="sk-cube2 sk-cube"></div><div class="sk-cube4 sk-cube"></div><div class="sk-cube3 sk-cube"></div></div></div>
				</div>
		</div>
		<!-- ---------------------- -->
		
		<header>
		
			<div class="logo">
				<a href="index.php">
					<img src="img/banner.png" height="85">
					<img src="img/bannermobile.png" height="50" >
				</a>
			</div>	
			
		</header>
		<main>
			<div class="container">
				
				<div class="playerinfo">
					
					<img src="https://ddragon.leagueoflegends.com/cdn/<?=$versions[0]?>/img/profileicon/<?=$player->profileIconId?>.png" onerror="this.src='img/noimg.jpg';">
					<h2><?=exist($player->name)?></h2>
					<div style="clear: both;"></div>
					<div class="playerlvl"><span class="red">LVL </span><span class="blue"><?=exist($player->summonerLevel)?></span></div>

					<!--<div class="league">
						<figure>
							<img src="img/bronze.png" alt="?" width="100">
						</figure>
						<figure>
							<img src="img/bronze.png" alt="?" width="100">
							<figcaption><span class="red">Solo Queue</span></figcaption>
							<figcaption><span class="blue">Bronz 5</span></figcaption>
						</figure>
						<figure>
							<img src="img/bronze.png" alt="?" width="100">
						</figure>
						<div style="clear: both;"></div>
					</div> -->
				</div>

				<div class="mastery">
					<h2><span class="blue">Maestrie </span><span class="red">Championów</span></h2>
					<div class="places">
						<figure>
							<figcaption class="place">I</figcaption>
							<!--OBRAZEK-->
							<img src="https://ddragon.leagueoflegends.com/cdn/<?=$versions[0]?>/img/champion/<?=$champions[$mastery[0]->championId]?>.png" onerror="this.src='img/noimg.jpg';">
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
							<img src="https://ddragon.leagueoflegends.com/cdn/<?=$versions[0]?>/img/champion/<?=$champions[$mastery[1]->championId]?>.png" onerror="this.src='img/noimg.jpg';">
							<!--NAKŁADKA Z PUNKTAMI-->
							<div class="overlay">
							<div class="oltext"><span class="blue"><?=exist($mastery[1]->championPoints)?></span><br><span class="red">PKT</span></div></div>
							<!--LVL-->
							<figcaption class="lvl">LVL <?=exist($mastery[1]->championLevel)?></figcaption>
						</figure>
						
						<figure class="place3">
							<figcaption class="place">III</figcaption>
							<!--OBRAZEK-->
							<img src="https://ddragon.leagueoflegends.com/cdn/<?=$versions[0]?>/img/champion/<?=$champions[$mastery[2]->championId]?>.png" onerror="this.src='img/noimg.jpg';">
							<!--NAKŁADKA Z PUNKTAMI-->
							<div class="overlay">
							<div class="oltext"><span class="blue"><?=exist($mastery[2]->championPoints)?></span><br><span class="red">PKT</span></div></div>
							<!--LVL-->
							<figcaption class="lvl">LVL <?=exist($mastery[2]->championLevel)?></figcaption>
						</figure>
						<div style="clear: both;"></div>
					</div>
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
								$m->lane = ($m->lane == 'NONE') ? '?' : $m->lane;
								echo '
								<div class="match">
								<img src="https://ddragon.leagueoflegends.com/cdn/'.$versions[0].'/img/champion/'.$champions[$m->champion].'.png" onerror="this.src=\'img/noimg.jpg\';">
								<div class="matchtext">
								<span class="blue">Linia: </span><span class="red">'.$m->lane.'</span><br>
								<span class="blue">Rozegrano: </span><span class="red">'.tstamp_to_days_ago($m->timestamp).'</span>
								</div>
								<div style="clear: both;"></div>
								</div>
								';
							}
						}
						print_r($league);
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