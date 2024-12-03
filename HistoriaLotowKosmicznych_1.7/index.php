<!DOCTYPE html>

<html lang="pl">

<?php
	include 'cfg.php';
?>

<html>
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="css/HistoriaLotowKosmicznych.css">
		<link rel="stylesheet" href="css/Wyslani w kosmos.css">
		<link rel="stylesheet" href="css/Statki kosmiczne.css">
		<link rel="stylesheet" href="css/Kontakt.css">
		<link rel="stylesheet" href="css/Planety_Karlowate.css">
		<link rel="stylesheet" href="css/Ksiezyce_Planet.css">
		<link rel="stylesheet" href="css/Historia.css">
		<link rel="stylesheet" href="css/Filmy.css">
		<link rel="shortcut icon" href="Image/Logo.jpg">
		<script src="js/timedate.js" type="text/javascript"> </script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"> </script>
	</head>
	
	<body onload="startclock()">
		<nav>
			<ul>
				<li><a href="index.php"> Strona główna </a></li>
			</ul>
			<ul>
				<li><a href="?idp=Historia"> Historia </a></li>
				<li><a href="?idp=Filmy"> Filmy </a></li>
			</ul>
			<ul>
				<li><a href=""> Ciała niebieskie </a>
					<ol>
						<li><a href="?idp=Planety"> Planety </a></li>
						<li><a href="?idp=Planety_Karlowate"> Planety karłowate </a></li>
						<li><a href="?idp=Ksiezyce_Planet"> Księżyce planet </a></li>
					</ol>
				</li>
			</ul>
			<ul>
				<li><a href=""> Galeria </a>
					<ol>
						<li><a href="?idp=Statki_kosmiczne"> Statki kosmiczne </a></li>
						<li><a href="?idp=Wyslani_w_kosmos"> Wysłani w kosmos (zwierzęta/ludzie) </a></li>
					</ol>
				</li>
			</ul>
			<ul>
				<li><a href="?idp=Kontakt"> Kontakt </a></li>
			</ul>
		</nav>
		<footer>
			<h4 id="zegarek"> </h4>
			<h4 id="data"> </h4>
			<h6>
			<?php
				$nr_indeksu='169249';
				$nrGrupy='1';
				
				echo 'Autor: Wojciech Czerniak'.$nr_indeksu.'grupa'.$nrGrupy.'<br /><br />';
			?>
			</h6>
			<h6> Wszelkie prawa zastrzeżone </h6>
		</footer>
	</body>
</html>