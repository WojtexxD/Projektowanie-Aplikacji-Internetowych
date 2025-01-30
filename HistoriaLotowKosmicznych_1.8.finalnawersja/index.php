<!DOCTYPE html>

<html lang="pl">

//wziecie informacji z pliku
<?php
	include 'cfg.php';
	if ($_GET['page'] == 16)
	{
		include('koszyk.php');
	}
	elseif (isset($_GET['page']))
	{
		include('showpage.php');
	}
	else
	{
		$_GET['page']=1;
		include('showpage.php');
	}
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
				<li><a href="?page=2"> Historia </a></li>
				<li><a href="?page=3"> Filmy </a></li>
			</ul>
			<ul>
				<li><a href=""> Ciała niebieskie </a>
					<ol>
						<li><a href="?page=4"> Planety </a></li>
						<li><a href="?page=5"> Planety karłowate </a></li>
						<li><a href="?page=6"> Księżyce planet </a></li>
					</ol>
				</li>
			</ul>
			<ul>
				<li><a href=""> Galeria </a>
					<ol>
						<li><a href="?page=7"> Statki kosmiczne </a></li>
						<li><a href="?page=8"> Wysłani w kosmos (zwierzęta/ludzie) </a></li>
					</ol>
				</li>
			</ul>
			<ul>
				<li><a href="?page=9"> Kontakt </a></li>
			</ul>
			<ul>
				<li><a href="?page=16"> Koszyk </a></li>
			</ul>
		</nav>
		
		<main>
        <?php
        $sql = "SELECT * FROM page_list WHERE id = ?";
			if ($stmt = $link->prepare($sql))
			{
					$stmt->bind_param("i", $page_id);
					$stmt->execute();
					$result = $stmt->get_result();
				if ($result->num_rows > 0)
				{
					$row = $result->fetch_assoc();
					$title = $row['page_title'];
					$content = $row['page_content'];
				}
				else
				{
					$title = "Strona nie znaleziona";
					$content = "Przepraszamy, ale taka strona nie istnieje.";
				}
				$stmt->close();
			}
			else
			{
				die("Błąd zapytania SQL: " . $conn->error);
			}
        ?>
    </main>
		

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