<?php
// Dołączenie pliku konfiguracyjnego z danymi do połączenia z bazą
include('../cfg.php');

// Dodawanie nowej kategorii
function DodajKategorie($nazwa, $matka = 0)
{
    global $link;  // Używamy obiektu połączenia z bazy danych
    $sql = "INSERT INTO kategorie (nazwa, matka) VALUES (?, ?)";
    $stmt = $link->prepare($sql);
    $stmt->bind_param('si', $nazwa, $matka);
    $stmt->execute();
    echo "Kategoria została dodana pomyślnie.<br>";
}

// Usuwanie kategorii
function UsunKategorie($id)
{
    global $link;
    
    // Usuwanie wszystkich podkategorii przypisanych do tej kategorii
    $sql = "DELETE FROM kategorie WHERE matka = ?";
    $stmt = $link->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    
    // Usuwanie samej kategorii
    $sql = "DELETE FROM kategorie WHERE id = ?";
    $stmt = $link->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    
    echo "Kategoria została usunięta wraz z podkategoriami (jeśli były).<br>";
}

// Edytowanie kategorii (zmiana nazwy)
function EdytujKategorie($id, $nowa_nazwa)
{
    global $link;
    $sql = "UPDATE kategorie SET nazwa = ? WHERE id = ?";
    $stmt = $link->prepare($sql);
    $stmt->bind_param('si', $nowa_nazwa, $id);
    $stmt->execute();
    echo "Kategoria została zaktualizowana.<br>";
}

// Wyświetlanie kategorii (drzewo kategorii)
function PokazKategorie()
{
    global $link;
    
    // Pobieramy wszystkie kategorie główne (matka = 0)
    $sql = "SELECT * FROM kategorie WHERE matka = 0";
    $result = $link->query($sql);
    
    // Wyświetlamy kategorie główne
    while ($kategoria = $result->fetch_assoc())
	{
        echo "Kategoria główna: " . $kategoria['nazwa'] . "<br>";
        
        // Teraz pobieramy podkategorie przypisane do tej kategorii
        $sql2 = "SELECT * FROM kategorie WHERE matka = ?";
        $stmt2 = $link->prepare($sql2);
        $stmt2->bind_param('i', $kategoria['id']);
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        
        // Wyświetlamy podkategorie
        while ($podkategoria = $result2->fetch_assoc())
		{
            echo "&nbsp;&nbsp;&nbsp;Podkategoria: " . $podkategoria['nazwa'] . "<br>";
        }
    }
}

// Przykładowe wywołania funkcji
// Dodanie kategorii
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['dodaj_kategorie']))
{
    $nazwa = $_POST['nazwa'];
    $matka = isset($_POST['matka']) ? $_POST['matka'] : 0;
    DodajKategorie($nazwa, $matka);
}

// Usunięcie kategorii
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['usun_kategorie']))
{
    $id = $_POST['id'];
    UsunKategorie($id);
}

// Edytowanie kategorii
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edytuj_kategorie']))
{
    $id = $_POST['id'];
    $nowa_nazwa = $_POST['nowa_nazwa'];
    EdytujKategorie($id, $nowa_nazwa);
}

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarządzanie kategoriami</title>
</head>
<body>
    <h1>Zarządzanie kategoriami</h1>

    <!-- Formularz dodawania kategorii -->
    <form method="POST">
        <h2>Dodaj kategorię</h2>
        <label for="nazwa">Nazwa kategorii:</label>
        <input type="text" id="nazwa" name="nazwa" required><br><br>
        <label for="matka">Id kategorii głównej (0 jeśli główna):</label>
        <input type="number" id="matka" name="matka" value="0"><br><br>
        <input type="submit" name="dodaj_kategorie" value="Dodaj kategorię">
    </form>

    <!-- Formularz usuwania kategorii -->
    <form method="POST">
        <h2>Usuń kategorię</h2>
        <label for="id">Id kategorii do usunięcia:</label>
        <input type="number" id="id" name="id" required><br><br>
        <input type="submit" name="usun_kategorie" value="Usuń kategorię">
    </form>

    <!-- Formularz edytowania kategorii -->
    <form method="POST">
        <h2>Edytuj kategorię</h2>
        <label for="id">Id kategorii do edytowania:</label>
        <input type="number" id="id" name="id" required><br><br>
        <label for="nowa_nazwa">Nowa nazwa kategorii:</label>
        <input type="text" id="nowa_nazwa" name="nowa_nazwa" required><br><br>
        <input type="submit" name="edytuj_kategorie" value="Edytuj kategorię">
    </form>

    <!-- Wyświetlanie kategorii -->
    <h2>Lista kategorii</h2>
    <?php PokazKategorie(); ?>
</body>
</html>
