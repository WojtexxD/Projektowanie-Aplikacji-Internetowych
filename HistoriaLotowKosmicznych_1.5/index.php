<!DOCTYPE html>

<?php
	error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
?>
<html lang="pl">

<html>
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="css/Kontakt.css">
		<link rel="stylesheet" href="css/HistoriaLotowKosmicznych.css">
		<link rel="shortcut icon" href="Image/Logo.jpg">
		<script src="js/timedate.js" type="text/javascript"> </script>
	</head>
	
	<body onload="startclock()">
		<nav>
			<ul>
				<li><a href="Subpages/Historia.html"> Historia </a></li>
			</ul>
			<ul>
				<li><a href=""> Ciała niebieskie </a>
					<ol>
						<li><a href="Subpages/Planety.html"> Planety </a></li>
						<li><a href="Subpages/Planety_Karlowate.html"> Planety karłowate </a></li>
						<li><a href="Subpages/Ksiezyce_Planet.html"> Księżyce planet </a></li>
					</ol>
				</li>
			</ul>
			<ul>
				<li><a href=""> Galeria </a>
					<ol>
						<li><a href="Subpages/Statki kosmiczne.html"> Statki kosmiczne </a></li>
						<li><a href="Subpages/Wyslani w kosmos.html"> Wysłani w kosmos (zwierzęta/ludzie) </a></li>
					</ol>
				</li>
			</ul>
			<ul>
				<li><a href="Subpages/Kontakt.html"> Kontakt </a></li>
			</ul>	
		</nav>
		
		<?php
			if($_GET['idp']=='Kontakt')$strona='Subpages/Kontakt.html';
			if($_GET['idp']=='')
			{
				$strona='Subpages/glowna.html';
			}
			
			include($strona);
			
		?>
		
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