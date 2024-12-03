<?php

	$login = "admin";
	$pass = "admin123";
	
	$dbhost='localhost';
	$dbuser='root';
	$dbpass='';
	$baza='moja_strona';
	
	$link=mysqli_connect($dbhost,$dbuser,$dbpass,$baza);
	if(!$link) echo '<b>przerwane połączenie</b>';
	if(!mysqli_select_db($link,$baza)) echo 'nie wybrano bazy';
	if (!$link)
	{
		die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
	}
?>