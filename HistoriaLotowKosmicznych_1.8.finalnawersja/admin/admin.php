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

<?php
include('../cfg.php'); 
// Dodawanie nowej kategorii
function DodajKategorie($nazwa, $matka = 0)
{
    global $link;
    $sql = "INSERT INTO kategorie (nazwa, matka) VALUES (?, ?)";
    $stmt = $link->prepare($sql);
    $stmt->bind_param('si', $nazwa, $matka);
    $stmt->execute();
    echo "Kategoria została dodana pomyślnie.<br>";
}

// Usuwanie kategorii i podkategorii
function UsunKategorie($id)
{
    global $link;
    $sql = "DELETE FROM kategorie WHERE matka = ?";
    $stmt = $link->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $sql = "DELETE FROM kategorie WHERE id = ?";
    $stmt = $link->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    echo "Kategoria została usunięta.<br>";
}

// Edycja kategorii (nazwa i matka)
function EdytujKategorie($id, $nowa_nazwa, $nowa_matka)
{
    global $link;
    $sql = "UPDATE kategorie SET nazwa = ?, matka = ? WHERE id = ?";
    $stmt = $link->prepare($sql);
    $stmt->bind_param('sii', $nowa_nazwa, $nowa_matka, $id);
    $stmt->execute();
    echo "Kategoria została zaktualizowana.<br>";
}

// Wyświetlanie kategorii w formie drzewa
function PokazKategorie($matka = 0, $poziom = 0)
{
    global $link;
    $sql = "SELECT * FROM kategorie WHERE matka = ?";
    $stmt = $link->prepare($sql);
    $stmt->bind_param('i', $matka);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($kategoria = $result->fetch_assoc())
	{
        echo str_repeat("-", $poziom * 2) . " [" . $kategoria['id'] . "] " . $kategoria['nazwa'] . "<br>";
        PokazKategorie($kategoria['id'], $poziom + 1);
    }
    
    // Wyświetlanie kategorii, które nie mają przypisanej matki
    if ($matka == 0)
	{
        $sql = "SELECT * FROM kategorie WHERE matka NOT IN (SELECT id FROM kategorie) AND matka != 0";
        $result = $link->query($sql);
        while ($kategoria = $result->fetch_assoc())
		{
            echo "(Inne) [" . $kategoria['id'] . "] " . $kategoria['nazwa'] . "<br>";
        }
    }
}

// Obsługa formularzy
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (isset($_POST['dodaj_kategorie']))
	{
        DodajKategorie($_POST['nazwa'], $_POST['matka']);
    }
	elseif (isset($_POST['usun_kategorie']))
	{
        UsunKategorie($_POST['id']);
    }
	elseif (isset($_POST['edytuj_kategorie']))
	{
        EdytujKategorie($_POST['id'], $_POST['nowa_nazwa'], $_POST['nowa_matka']);
    }
}

// Interaktywna konsola w przeglądarce
echo "<h1>Zarządzanie kategoriami</h1>";
echo "<form method='POST'>
    <h2>Dodaj kategorię</h2>
    Nazwa: <input type='text' name='nazwa' required>
    Matka (ID): <input type='number' name='matka' value='0'>
    <input type='submit' name='dodaj_kategorie' value='Dodaj'>
</form>";
echo "<form method='POST'>
    <h2>Usuń kategorię</h2>
    ID: <input type='number' name='id' required>
    <input type='submit' name='usun_kategorie' value='Usuń'>
</form>";
echo "<form method='POST'>
    <h2>Edytuj kategorię</h2>
    ID: <input type='number' name='id' required>
    Nowa nazwa: <input type='text' name='nowa_nazwa' required>
    Nowa matka (ID): <input type='number' name='nowa_matka' required>
    <input type='submit' name='edytuj_kategorie' value='Edytuj'>
</form>";
echo "<h2>Lista kategorii</h2>";
PokazKategorie();
?>

<?php
include('../cfg.php'); 

// Dodawanie nowego produktu
function DodajProdukt($tytul, $opis, $cena_netto, $vat, $ilosc, $status, $kategoria, $gabaryt, $zdjecie, $data_wygasniecia)
{
    global $link;
    $sql = "INSERT INTO produkty (tytul, opis, cena_netto, podatek_vat, ilosc_magazyn, status_dostepnosci, kategoria, gabaryt, zdjecie, data_utworzenia, data_modyfikacji, data_wygasniecia) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?)";

    if ($stmt = $link->prepare($sql))
	{
        $stmt->bind_param("ssddiissss", $tytul, $opis, $cena_netto, $vat, $ilosc, $status, $kategoria, $gabaryt, $zdjecie, $data_wygasniecia);
        $stmt->execute();
        $stmt->close();
        echo "Produkt został dodany pomyślnie.<br>";
    }
	else
	{
        echo "Błąd dodawania produktu: " . $link->error . "<br>";
    }
}

// Usuwanie produktu
function UsunProdukt($id)
{
    global $link;
    $sql = "DELETE FROM produkty WHERE id = ?";

    if ($stmt = $link->prepare($sql))
	{
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        echo "Produkt został usunięty.<br>";
    }
	else
	{
        echo "Błąd usuwania produktu: " . $link->error . "<br>";
    }
}

// Edycja produktu
function EdytujProdukt($id, $tytul, $opis, $cena_netto, $vat, $ilosc, $status, $kategoria, $gabaryt, $zdjecie, $data_wygasniecia)
{
    global $link;
    $sql = "UPDATE produkty SET tytul = ?, opis = ?, cena_netto = ?, podatek_vat = ?, ilosc_magazyn = ?, status_dostepnosci = ?, kategoria = ?, gabaryt = ?, zdjecie = ?, data_modyfikacji = NOW(), data_wygasniecia = ? 
            WHERE id = ?";

    if ($stmt = $link->prepare($sql))
	{
        $stmt->bind_param("ssddiissssi", $tytul, $opis, $cena_netto, $vat, $ilosc, $status, $kategoria, $gabaryt, $zdjecie, $data_wygasniecia, $id);
        $stmt->execute();
        $stmt->close();
        echo "Produkt został zaktualizowany.<br>";
    }
	else
	{
        echo "Błąd edytowania produktu: " . $link->error . "<br>";
    }
}

// Sprawdzenie dostępności produktu
function SprawdzDostepnosc($ilosc, $status, $data_wygasniecia)
{
    if ($status == 'niedostępne' || $ilosc <= 0 || (!empty($data_wygasniecia) && strtotime($data_wygasniecia) < time()))
	{
        return 'Niedostępny';
    }
    return 'Dostępny';
}

function ObliczCeneBrutto($cena_netto, $podatek_vat)
{
    $cena_brutto = $cena_netto * (1 + $podatek_vat / 100);
    return number_format(ceil($cena_brutto * 100) / 100, 2, '.', '');
}
// Wyświetlanie produktów
function PokazProdukty()
{
    global $link;
    $sql = "SELECT produkty.*, kategorie.nazwa AS kategoria_nazwa 
            FROM produkty 
            LEFT JOIN kategorie ON produkty.kategoria = kategorie.id"; 

    $result = $link->query($sql);

    echo "<table border='1' cellspacing='0' cellpadding='5'>
            <tr>
                <th>ID</th>
                <th>Tytuł</th>
                <th>Opis</th>
                <th>Cena netto</th>
                <th>VAT</th>
                <th>Cena brutto</th>
                <th>Ilość</th>
                <th>Status</th>
                <th>Kategoria</th>
                <th>Gabaryt</th>
                <th>Data wygaśnięcia</th>
                <th>Obrazek</th>
            </tr>";

    while ($produkt = $result->fetch_assoc())
	{
		$dostepnosc = SprawdzDostepnosc($produkt['ilosc_magazyn'], $produkt['status_dostepnosci'], $produkt['data_wygasniecia']);
        $cena_brutto = ObliczCeneBrutto($produkt['cena_netto'], $produkt['podatek_vat']);
        
        echo "<tr>
                <td>{$produkt['id']}</td>
                <td>{$produkt['tytul']}</td>
                <td>{$produkt['opis']}</td>
                <td>{$produkt['cena_netto']} PLN</td>
                <td>{$produkt['podatek_vat']}%</td>
                <td>{$cena_brutto} PLN</td>
                <td>{$produkt['ilosc_magazyn']}</td>
                <td>{$dostepnosc}</td>
                <td>{$produkt['kategoria_nazwa']}</td> 
                <td>{$produkt['gabaryt']}</td>
                <td>{$produkt['data_wygasniecia']}</td>
                <td><img src='{$produkt['zdjecie']}' alt='Zdjęcie produktu' style='max-width: 100px;'></td>
              </tr>";
    }

    echo "</table>";
}



// Obsługa formularzy
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (isset($_POST['dodaj_produkt']))
	{
        DodajProdukt($_POST['tytul'], $_POST['opis'], $_POST['cena_netto'], $_POST['podatek_vat'], $_POST['ilosc_magazyn'], $_POST['status_dostepnosci'], $_POST['kategoria'], $_POST['gabaryt'], $_POST['zdjecie'], $_POST['data_wygasniecia']);
    }
	elseif (isset($_POST['usun_produkt']))
	{
        UsunProdukt($_POST['id']);
    }
	elseif (isset($_POST['edytuj_produkt']))
	{
        EdytujProdukt($_POST['id'], $_POST['tytul'], $_POST['opis'], $_POST['cena_netto'], $_POST['podatek_vat'], $_POST['ilosc_magazyn'], $_POST['status_dostepnosci'], $_POST['kategoria'], $_POST['gabaryt'], $_POST['zdjecie'], $_POST['data_wygasniecia']);
    }
}

// Interfejs w PHP
echo "<h1>Zarządzanie produktami</h1>";

// Formularz dodawania produktu
echo "<form method='POST'>
    <h2>Dodaj produkt</h2>
    <input type='text' name='tytul' placeholder='Tytuł' required>
    <input type='text' name='opis' placeholder='Opis' required>
    <input type='number' step='0.01' name='cena_netto' placeholder='Cena netto' required>
    <input type='number' step='0.01' name='podatek_vat' placeholder='VAT' required>
    <input type='number' name='ilosc_magazyn' placeholder='Ilość' required>
    <select name='status_dostepnosci' required>
        <option value='dostępne'>Dostępne</option>
        <option value='niedostępne'>Niedostępne</option>
        <option value='na zamówienie'>Na zamówienie</option>
    </select>
    <input type='text' name='kategoria' placeholder='id kategorii' required>
    <input type='text' name='gabaryt' placeholder='Gabaryt' required>
    <input type='text' name='zdjecie' placeholder='Zdjęcie (URL)'>
    <input type='date' name='data_wygasniecia'>
    <input type='submit' name='dodaj_produkt' value='Dodaj'>
</form>";

// Formularz usuwania
echo "<form method='POST'>
    <h2>Usuń produkt</h2>
    <input type='number' name='id' placeholder='ID' required>
    <input type='submit' name='usun_produkt' value='Usuń'>
</form>";

// Formularz edytowania produktu
echo "<form method='POST'>
    <h2>Edytuj produkt</h2>
    <input type='number' name='id' placeholder='ID produktu' required>
    <input type='text' name='tytul' placeholder='Tytuł' required>
    <input type='text' name='opis' placeholder='Opis' required>
    <input type='number' step='0.01' name='cena_netto' placeholder='Cena netto' required>
    <input type='number' step='0.01' name='podatek_vat' placeholder='VAT(% od ceny)' required>
    <input type='number' name='ilosc_magazyn' placeholder='Ilość' required>
    <select name='status_dostepnosci' required>
        <option value='dostępne'>Dostępne</option>
        <option value='niedostępne'>Niedostępne</option>
        <option value='na zamówienie'>Na zamówienie</option>
    </select>
    <input type='text' name='kategoria' placeholder='id kategorii' required>
    <input type='text' name='gabaryt' placeholder='Gabaryt' required>
    <input type='text' name='zdjecie' placeholder='Zdjęcie (URL)'>
    <input type='date' name='data_wygasniecia'>
    <input type='submit' name='edytuj_produkt' value='Edytuj'>
</form>";


// Wyświetlanie produktów
echo "<h2>Lista produktów</h2>";
PokazProdukty();
?>
