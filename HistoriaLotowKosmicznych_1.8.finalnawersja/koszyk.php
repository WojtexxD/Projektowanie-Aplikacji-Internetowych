<?php
session_start();
include 'cfg.php';

// Obsługa dodawania do koszyka
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    if (isset($_POST['dodaj']))
	{
        $id = $_POST['id'];
        $ilosc = (int) $_POST['ilosc'];

        // Pobranie ilości dostępnej w magazynie
        $stmt = $link->prepare("SELECT ilosc_magazyn FROM produkty WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $produkt = $result->fetch_assoc();

        if ($produkt && $ilosc > 0 && $ilosc <= $produkt['ilosc_magazyn'])
		{
            $_SESSION['koszyk'][$id] = $ilosc;
        }
    }

    if (isset($_POST['usun']))
	{
        $id = $_POST['id'];
        unset($_SESSION['koszyk'][$id]);
    }

    if (isset($_POST['zmien']))
	{
        $id = $_POST['id'];
        $ilosc = (int) $_POST['ilosc'];

        // Pobranie ilości dostępnej w magazynie
        $stmt = $link->prepare("SELECT ilosc_magazyn FROM produkty WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $produkt = $result->fetch_assoc();

        if ($produkt && $ilosc > 0 && $ilosc <= $produkt['ilosc_magazyn'])
		{
            $_SESSION['koszyk'][$id] = $ilosc;
        }
    }

    // Zapobiega zmianie adresu URL
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit;
}

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Sklep - Koszyk</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin: 20px 0; font-size: 18px; text-align: left; }
        th, td { padding: 12px; border: 1px solid black; }
        th { background-color: #111111; }
    </style>
</head>
<body>

<h1>Lista Produktów</h1>
<table>
    <tr>
        <th>Zdjęcie</th>
        <th>ID</th>
        <th>Nazwa</th>
        <th>Cena netto</th>
        <th>VAT</th>
        <th>Cena brutto</th>
        <th>Ilość</th>
        <th>Dodaj do koszyka</th>
    </tr>

    <?php
    $result = mysqli_query($link, "SELECT * FROM produkty");

    while ($produkt = mysqli_fetch_assoc($result))
	{
        $cena_brutto = round($produkt['cena_netto'] * (1 + $produkt['podatek_vat'] / 100), 2);

        echo "<tr>
        <td><img src='{$produkt['zdjecie']}' alt='Zdjęcie' style='max-width: 100px;'></td>
        <td>{$produkt['id']}</td>
        <td>{$produkt['tytul']}</td>
        <td>{$produkt['cena_netto']} PLN</td>
        <td>{$produkt['podatek_vat']}%</td>
        <td>{$cena_brutto} PLN</td>
        <td>{$produkt['ilosc_magazyn']}</td>
        <td>
            <form method='post'>
                <input type='hidden' name='id' value='{$produkt['id']}'>
                <input type='number' name='ilosc' min='1' max='{$produkt['ilosc_magazyn']}' value='1' required>
                <button type='submit' name='dodaj'>Dodaj</button>
            </form>
        </td>
      </tr>";
    }
    ?>
</table>

<h1>Twój Koszyk</h1>
<table>
    <tr>
        <th>Zdjęcie</th> <!-- Kolumna dodana przed ID -->
        <th>ID</th>
        <th>Nazwa</th>
        <th>Cena brutto</th>
        <th>Ilość</th>
        <th>Razem</th>
        <th>Akcje</th>
    </tr>

    <?php
    $suma_do_zaplaty = 0;

    if (!empty($_SESSION['koszyk']))
	{
        foreach ($_SESSION['koszyk'] as $id => $ilosc)
		{
            $stmt = $link->prepare("SELECT id, tytul, cena_netto, podatek_vat, ilosc_magazyn, zdjecie FROM produkty WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $produkt = $result->fetch_assoc();

            if ($produkt)
			{
                $cena_brutto = round($produkt['cena_netto'] * (1 + $produkt['podatek_vat'] / 100), 2);
                $razem = $cena_brutto * (int)$ilosc;
                $suma_do_zaplaty += $razem;

                echo "<tr>
                        <td><img src='{$produkt['zdjecie']}' alt='Zdjęcie' style='max-width: 100px;'></td> <!-- Zdjęcie dodane -->
                        <td>{$produkt['id']}</td>
                        <td>{$produkt['tytul']}</td>
                        <td>{$cena_brutto} PLN</td>
                        <td>
                            <form method='post' style='display:inline;'>
                                <input type='hidden' name='id' value='{$id}'>
                                <input type='number' name='ilosc' min='1' max='{$produkt['ilosc_magazyn']}' value='{$ilosc}' required>
                                <button type='submit' name='zmien'>Zmień</button>
                            </form>
                        </td>
                        <td>{$razem} PLN</td>
                        <td>
                            <form method='post' style='display:inline;'>
                                <input type='hidden' name='id' value='{$id}'>
                                <button type='submit' name='usun'>Usuń</button>
                            </form>
                        </td>
                      </tr>";
            }
        }
        echo "<tr><td colspan='5'>Razem do zapłaty:</td><td>{$suma_do_zaplaty} PLN</td><td></td></tr>";
    }
	else
	{
        echo "<tr><td colspan='7'>Koszyk jest pusty.</td></tr>";
    }
    ?>
</table>


</body>
</html>
