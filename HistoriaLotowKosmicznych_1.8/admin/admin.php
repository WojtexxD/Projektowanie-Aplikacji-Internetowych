<?php
	//zapamietuje dana sesje i wczytuje dane z pliku
	session_start();
	require_once '../cfg.php';
	// formularz ktory jest od logowania sie
	function FormularzLogowania()
	{
		// wziete z pliku cfg.php
		global $login, $pass;
		// logowanie i sprawdzenie czy taka nazwa z haslem sa poprawne
		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$entered_login = isset($_POST['login']) ? trim($_POST['login']) : '';
			$entered_pass = isset($_POST['password']) ? trim($_POST['password']) : '';

			if ($entered_login === $login && $entered_pass === $pass)
			{
				$_SESSION['logged_in'] = true;
				header('Location: admin.php');
				exit();
			}
			else
			{
				echo '<p style="color:red;">Nieprawidłowy login lub hasło.</p>';
			}
		}
		// formularz do logowania
		echo '<h2>Logowanie</h2>';
		echo '<form action="" method="post">';
		echo '<label for="login">Login:</label><br>';
		echo '<input type="text" id="login" name="login" required><br><br>';
		echo '<label for="password">Hasło:</label><br>';
		echo '<input type="password" id="password" name="password" required><br><br>';
		echo '<input type="submit" value="Zaloguj">';
		echo '</form>';
	}

	if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true)
	{
		FormularzLogowania();
		exit();
	}
	
	echo '<h1>Panel administracyjny</h1>';
	// po opcjii robi to co wybralismy (usuwa, edytuje, dodaje nowa strone)
	if (isset($_GET['delete']))
	{
		$id = intval($_GET['delete']);
		UsunPodstrone($id);
	}
	
	if (isset($_GET['edit']))
	{
		$id = intval($_GET['edit']);
		EdytujPodstrone($id);
		exit();
	}
	
	if (isset($_GET['add_new']))
	{
		DodajNowaPodstrone();
		exit();
	}
	// pokazuje liste wszystkich stron/podstron ktore sa w bazie danych z guzikami usun edytuj i dodaj
	function ListaPodstron()
	{
		global $link;

		$query = "SELECT id, page_title FROM page_list";
		$result = mysqli_query($link, $query);

		if (!$result)
		{
			die("Błąd zapytania: " . mysqli_error($link));
		}
		// tabela pod wyswietlenie id stron i ich nazw
		echo '<h2>Lista podstron</h2>';
		echo '<table border="1" cellpadding="5">';
		echo '<tr><th>ID</th><th>Tytuł podstrony</th><th>Akcje</th></tr>';
		// wyswietlenie id i nazw stron
		while ($row = mysqli_fetch_assoc($result))
		{
			echo '<tr>';
			echo '<td>' . htmlspecialchars($row['id']) . '</td>';
			echo '<td>' . htmlspecialchars($row['page_title']) . '</td>';
			echo '<td>';
			echo '<a href="admin.php?edit=' . $row['id'] . '">Edytuj</a> | ';
			echo '<a href="admin.php?delete=' . $row['id'] . '" onclick="return confirm(\'Czy na pewno chcesz usunąć tę podstronę?\');">Usuń</a>';
			echo '</td>';
			echo '</tr>';
		}
		// guzik od dodawania stron
		echo '<tr>';
		echo '<td colspan="3" style="text-align: center;">';
		echo '<a href="admin.php?add_new" class="button">Dodaj podstronę</a>';
		echo '</td>';
		echo '</tr>';
		
		echo '</table>';
		
		mysqli_free_result($result);
}





	// funkcja odpowiedzialna od edytowania stron z baz danych
	function EdytujPodstrone($id)
	{
		global $link;
		
		$query = "SELECT page_title, page_content, status FROM page_list WHERE id = ?";
		$stmt = mysqli_prepare($link, $query);
		mysqli_stmt_bind_param($stmt, "i", $id);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);

		if ($row = mysqli_fetch_assoc($result))
		{
			echo '<form action="update_page_list.php" method="post">';
			echo '<input type="hidden" name="id" value="' . htmlspecialchars($id) . '">';
			echo '<label for="title">Tytuł:</label>';
			echo '<input type="text" id="title" name="title" value="' . htmlspecialchars($row['page_title']) . '">';
			echo '<br><label for="page_content">Treść:</label>';
			echo '<textarea id="page_content" name="page_content">' . htmlspecialchars($row['page_content']) . '</textarea>';
			echo '<br><label for="status">Aktywna:</label>';
			echo '<input type="checkbox" id="status" name="status"' . ($row['status'] ? ' checked' : '') . '>';
			echo '<br><input type="submit" value="Zapisz">';
			echo '</form>';
		}
		else
		{
			echo 'Podstrona o podanym ID nie istnieje.';
		}

		mysqli_stmt_close($stmt);
	}
	
	
	
	
	// funkcja odpowiedzialna za dodawanie nowych stron
	function DodajNowaPodstrone()
	{
		global $link;

		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$page_title = isset($_POST['page_title']) ? trim($_POST['page_title']) : null;
			$page_content = isset($_POST['page_content']) ? trim($_POST['page_content']) : null;
			$status = isset($_POST['status']) ? 1 : 0;

			if (empty($page_title) || empty($page_content))
			{
				echo "Tytuł i treść podstrony są wymagane.";
				return;
			}

			$query = "INSERT INTO page_list (page_title, page_content, status) VALUES (?, ?, ?)";
			$stmt = mysqli_prepare($link, $query);

			if (!$stmt)
			{
				die("Błąd przygotowania zapytania SQL: " . mysqli_error($link));
			}

			mysqli_stmt_bind_param($stmt, "ssi", $page_title, $page_content, $status);

			if (mysqli_stmt_execute($stmt))
			{
				echo "Nowa podstrona została dodana pomyślnie.";
			}
			else
			{
				echo "Wystąpił błąd podczas dodawania podstrony: " . mysqli_error($link);
			}

			mysqli_stmt_close($stmt);
		}
		
		echo '<h2>Dodaj nową podstronę</h2>';
		echo '<form action="" method="post">';
		echo '<label for="page_title">Tytuł strony:</label><br>';
		echo '<input type="text" id="page_title" name="page_title" required><br><br>';
		echo '<label for="page_content">Treść:</label><br>';
		echo '<textarea id="page_content" name="page_content" rows="5" cols="30" required></textarea><br><br>';
		echo '<label for="status">Aktywna:</label>';
		echo '<input type="checkbox" id="status" name="status"><br><br>';
		echo '<input type="submit" value="Dodaj">';
		echo '</form>';
	}
	
	
	// funkcja od usuwania stron
	function UsunPodstrone($id)
	{
		global $link;

		$id = intval($id);

		$query = "DELETE FROM page_list WHERE id = ?";
		$stmt = mysqli_prepare($link, $query);

		if ($stmt)
		{
			mysqli_stmt_bind_param($stmt, "i", $id);

			if (mysqli_stmt_execute($stmt))
			{
				echo "<p>Podstrona o ID $id została pomyślnie usunięta.</p>";
			}
			else
			{
				echo "<p>Błąd podczas usuwania podstrony: " . mysqli_error($link) . "</p>";
			}

			mysqli_stmt_close($stmt);
		}
		else
		{
			echo "<p>Błąd przygotowania zapytania: " . mysqli_error($link) . "</p>";
		}
	}
	ListaPodstron();
?>


