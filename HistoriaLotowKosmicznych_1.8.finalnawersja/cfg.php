<?php
	//login i haslo do bazy danych
	$login = "admin";
	$pass = "admin123";
	// nazwa bazy danych tabeli i haslo z loginem
	$dbhost='localhost';
	$dbuser='root';
	$dbpass='';
	$baza='moja_strona';
	// laczy z baza danych i sprawdza czy wszystko sie zgadza i jest w stanie sie polaczyc
	$link=mysqli_connect($dbhost,$dbuser,$dbpass,$baza);
	if(!$link) echo '<b>przerwane połączenie</b>';
	if(!mysqli_select_db($link,$baza)) echo 'nie wybrano bazy';
	if (!$link)
	{
		die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
	}
?>