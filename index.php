<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>

	<title>CheckSummoner - Statystyki graczy League of Legends</title>
	<meta name="description" content="Sprawdź statystyki graczy League of Legends z całego świata - szybko i bezproblemowo">
	<meta name="keywords" content="league, legends, lol, statystyki, mecze, summoner">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="stylesheet" href="main.css">
	<link rel="stylesheet" href="spinner.css">
	<link href="https://fonts.googleapis.com/css?family=Lato:400,900&amp;subset=latin-ext" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src='https://www.google.com/recaptcha/api.js'></script>
	<script src='loader.js'></script>

	<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.css">
	<script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.js"></script>
	<script src="cookies.js"></script>

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
			<img src="img/banner.png">
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
						<option value="na1" <?php if(isset($_SESSION[ 'select']) && $_SESSION[ 'select']=='na1' ) { echo 'selected'; unset($_SESSION[
						    'select']);} ?>>NA</div>
			</option>
			<option value="eun1" <?php if(isset($_SESSION[ 'select']) && $_SESSION[ 'select']=='eun1' ) { echo 'selected'; unset($_SESSION[
			    'select']);} ?>>EUNE</option>
			<option value="euw1" <?php if(isset($_SESSION[ 'select']) && $_SESSION[ 'select']=='euw1' ) { echo 'selected'; unset($_SESSION[
			    'select']);} ?>>EUW</option>
			<option value="oc1" <?php if(isset($_SESSION[ 'select']) && $_SESSION[ 'select']=='oc1' ) { echo 'selected'; unset($_SESSION[
			    'select']);} ?>>OCE</option>
			<option value="la1" <?php if(isset($_SESSION[ 'select']) && $_SESSION[ 'select']=='la1' ) { echo 'selected'; unset($_SESSION[
			    'select']);} ?>>LAN</option>
			<option value="la2" <?php if(isset($_SESSION[ 'select']) && $_SESSION[ 'select']=='la2' ) { echo 'selected'; unset($_SESSION[
			    'select']);} ?>>LAS</option>
			<option value="br1" <?php if(isset($_SESSION[ 'select']) && $_SESSION[ 'select']=='br1' ) { echo 'selected'; unset($_SESSION[
			    'select']);} ?>>BR</option>
			<option value="jp1" <?php if(isset($_SESSION[ 'select']) && $_SESSION[ 'select']=='jp1' ) { echo 'selected'; unset($_SESSION[
			    'select']);} ?>>JP</option>
			<option value="kr" <?php if(isset($_SESSION[ 'select']) && $_SESSION[ 'select']=='kr' ) { echo 'selected'; unset($_SESSION[
			    'select']);} ?>>KR</option>
			<option value="tr1" <?php if(isset($_SESSION[ 'select']) && $_SESSION[ 'select']=='tr1' ) { echo 'selected'; unset($_SESSION[
			    'select']);} ?>>TR</option>
			<option value="ru" <?php if(isset($_SESSION[ 'select']) && $_SESSION[ 'select']=='ru' ) { echo 'selected'; unset($_SESSION[
			    'select']);} ?>>RU</option>
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