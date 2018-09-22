<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pl">
	
	<head>
		
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-122734777-1"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());
			
			gtag('config', 'UA-122734777-1');
		</script>
		
		<!--  Clear Cache -->
		<meta http-equiv="cache-control" content="max-age=0" />
		<meta http-equiv="cache-control" content="no-cache" />
		<meta http-equiv="expires" content="0" />
		<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
		<meta http-equiv="pragma" content="no-cache" />
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0" >
		
		<title>CheckSummoner - Statystyki graczy League of Legends</title>
		<meta name="description" content="Sprawdź statystyki graczy League of Legends z całego świata - szybko i bezproblemowo. CheckSummoner to wyjtkowo prosta w obsłudze strona, dzięki której jesteś w stanie sprawdzić statystyki danego gracza.">
		<meta name="keywords" content="league, legends, lol, statystyki, mecze, summoner, stats, check, summoner, checksummoner, ga, ShatterPlayer">
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="google-site-verification" content="6FgJsje9_OIRxLLq-1ml2ouyzm5lsUut3Nj7U9qqJps" />
		<link rel="stylesheet" href="css/main.css">
		<link rel="stylesheet" href="css/spinner.css">
		<link href="https://fonts.googleapis.com/css?family=Lato:400,900&amp;subset=latin-ext" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src='https://www.google.com/recaptcha/api.js'></script>
		<script src='js/loader.js'></script>
		
		<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.css">
		<script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.js"></script>
		<script src="js/cookies.js"></script>
		
		<link rel="stylesheet" type="text/css" href="css/preloader.css">
		<script src="js/preloader.js"></script>
		
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
				<div class="loader">
					<div class="sk-folding-cube">
						<div class="sk-cube1 sk-cube"></div>
						<div class="sk-cube2 sk-cube"></div>
						<div class="sk-cube4 sk-cube"></div>
						<div class="sk-cube3 sk-cube"></div>
					</div>
				</div>
			</div>
		</div>
		<!-- 						-->
		
		<header>
			
			<div class="logo">
				<img src="img/banner.png" height="85">
				<img src="img/bannermobile.png"height="50" >
			</div>
			
		</header>
		
		<main>
			
			<div class="maintext">
				<h2>Statystyki graczy League of Legends</h2>
				<p>Prosto z oficjalnych serwerów gry</p>
			</div>
			
			<div class="paraminput" id="error">
				<h2>Podaj dane gracza</h2>
				<div class="inputscontainer">
					<form action="stats.php" method="POST" onsubmit="setSpinner()">
						<input name="nick" type="text" placeholder="NICK" onfocus="this.placeholder=''" onblur="this.placeholder='NICK'" <?php if(isset($_SESSION[
						'nick'])) {echo 'value="'.$_SESSION[ 'nick']. '"'; unset($_SESSION[ 'nick']);} ?>>
						<select name="region">
							<option value="na1" <?php if(isset($_SESSION[ 'select']) && $_SESSION[ 'select']=='na1' ) { echo 'selected'; unset($_SESSION['select']);} ?>>NA</option>
							<option value="eun1" <?php if(isset($_SESSION[ 'select']) && $_SESSION[ 'select']=='eun1' ) { echo 'selected'; unset($_SESSION['select']);} ?>>EUNE</option>
							<option value="euw1" <?php if(isset($_SESSION[ 'select']) && $_SESSION[ 'select']=='euw1' ) { echo 'selected'; unset($_SESSION['select']);} ?>>EUW</option>
							<option value="oc1" <?php if(isset($_SESSION[ 'select']) && $_SESSION[ 'select']=='oc1' ) { echo 'selected'; unset($_SESSION['select']);} ?>>OCE</option>
							<option value="la1" <?php if(isset($_SESSION[ 'select']) && $_SESSION[ 'select']=='la1' ) { echo 'selected'; unset($_SESSION['select']);} ?>>LAN</option>
							<option value="la2" <?php if(isset($_SESSION[ 'select']) && $_SESSION[ 'select']=='la2' ) { echo 'selected'; unset($_SESSION['select']);} ?>>LAS</option>
							<option value="br1" <?php if(isset($_SESSION[ 'select']) && $_SESSION[ 'select']=='br1' ) { echo 'selected'; unset($_SESSION['select']);} ?>>BR</option>
							<option value="jp1" <?php if(isset($_SESSION[ 'select']) && $_SESSION[ 'select']=='jp1' ) { echo 'selected'; unset($_SESSION['select']);} ?>>JP</option>
							<option value="kr" <?php if(isset($_SESSION[ 'select']) && $_SESSION[ 'select']=='kr' ) { echo 'selected'; unset($_SESSION['select']);} ?>>KR</option>
							<option value="tr1" <?php if(isset($_SESSION[ 'select']) && $_SESSION[ 'select']=='tr1' ) { echo 'selected'; unset($_SESSION['select']);} ?>>TR</option>
							<option value="ru" <?php if(isset($_SESSION[ 'select']) && $_SESSION[ 'select']=='ru' ) { echo 'selected'; unset($_SESSION['select']);} ?>>RU</option>
						</select>
						<input type="submit" value="SPRAWDŹ STATYSTYKI">
						<div style="clear: both"></div>
						<div class="g-recaptcha" data-sitekey="6LeJx18UAAAAAIG-Zq103qn7fZHOuT74GDrwgvga" data-theme="dark"></div>
						<div class="errorcontainer">
							<?php 
								if(isset($_SESSION['error']))
								{
									echo '<h3 class="error">'.$_SESSION['error'].'</h3>';
									unset($_SESSION['error']);
								}
							?>
						</div>
					</form>
					<img class="qmark" src="img/qmark.jpg" onmouseover="document.getElementById('serverinfo').style.opacity = 1;" onmouseout="document.getElementById('serverinfo').style.opacity = 0;">
					<div id="serverinfo">
						<b>NA</b> - Ameryka Północna
						<br>
						<b>EUNE</b> - Europa Północ/Wschód
						<br>
						<b>EUW</b> - Europa Zachód
						<br>
						<b>OCE</b> - Oceania
						<br>
						<b>LAN</b> - Ameryka Łacińska - Północ
						<br>
						<b>LAS</b> - Ameryka Łacińska - Południe
						<br>
						<b>BR</b> - Brazylia
						<br>
						<b>JP</b> - Japonia
						<br>
						<b>KR</b> - Korea Południowa
						<br>
						<b>TR</b> - Turcja
						<br>
						<b>RU</b> - Rosja
						<br>
					</div>
					
				</div>
				
				<img src="img/dariusblue.png" onerror="this.style.opacity='0';" class="img1">
				<img src="img/dariusred.png" onerror="this.style.opacity='0';" class="img2">
				
			</main>
			
			<footer>
				<div class="footer">
					<span class="blue">Check</span>
					<span class="red">Summoner</span> &copy; - Statystyki graczy League of Legends
					<br> Kontakt: shatterplayer@gmail.com
				</div>
			</footer>
			
		</body>
		
	</html>	