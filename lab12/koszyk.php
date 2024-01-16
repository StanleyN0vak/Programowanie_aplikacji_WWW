<?php
session_start();

$connection = mysqli_connect("localhost", "root", "", "moja_strona");

if (!$connection) {
    die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
}

// Dodawanie produktu do koszyka
if (isset($_POST['dodajProdukt'])) {
    $id_prod = $_POST['produkt'];
    $ile_sztuk = $_POST['ile_sztuk'];
    dodajDoKoszyka($id_prod, $ile_sztuk);
}

// Funkcja dodawania produktu do koszyka
function dodajDoKoszyka($id_prod, $ile_sztuk)
{
    global $connection;

    // Pobierz informacje o produkcie z bazy danych
    $query = "SELECT * FROM produkty WHERE id = $id_prod";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        $produkt = array(
            'id_prod' => $row['id'],
            'tytul' => $row['tytul'],
            'opis' => $row['opis'],
            'cena_netto' => $row['cena_netto'],
            'podatek_vat' => $row['podatek_vat'],
            'ile_sztuk' => $ile_sztuk
        );

        $_SESSION['koszyk'][] = $produkt;
    }
}

if (isset($_POST['usunProdukt'])) {
    $id_produktu_do_usuniecia = $_POST['id_produktu_do_usuniecia'];
    usunZKoszyka($id_produktu_do_usuniecia);
}

// Funkcja usuwania produktu z koszyka
function usunZKoszyka($id_prod)
{
    foreach ($_SESSION['koszyk'] as $indeks => $produkt) {
        if ($produkt['id_prod'] == $id_prod) {
            unset($_SESSION['koszyk'][$indeks]);
            break;
        }
    }
    $_SESSION['koszyk'] = array_values($_SESSION['koszyk']);
}

if (isset($_POST['nowa_ilosc'])) {
    $id_prod = $_POST['id_prod'];
    $ile_sztuk = $_POST['ile_sztuk'];
    aktualizujIlosc($id_prod, $ile_sztuk);
}

// Funkcja aktualizacji ilości produktu w koszyku
function aktualizujIlosc($id_prod, $nowaIlosc)
{
    foreach ($_SESSION['koszyk'] as $indeks => $produkt) {
        if ($produkt['id_prod'] == $id_prod) {
            $_SESSION['koszyk'][$indeks]['ile_sztuk'] = $nowaIlosc;
            break;
        }
    }
}

// Funkcja obliczania całkowitej wartości produktów w koszyku
function obliczCalkowitaWartosc()
{
    $calkowitaWartosc = 0;

    foreach ($_SESSION['koszyk'] as $produkt) {
        // Oblicz cenę brutto
        $cenaBrutto = $produkt['cena_netto'] * (1 + $produkt['podatek_vat'] / 100);

        // Oblicz całkowitą wartość
        $calkowitaWartosc += $cenaBrutto * $produkt['ile_sztuk'];
    }

    return $calkowitaWartosc;
}

$query = "SELECT id, tytul FROM produkty";
$result = mysqli_query($connection, $query);
?>

<style>
    <?php include 'css/myStyle.css'; ?>
</style>

<form method="post" action="koszyk.php">
    <label for="produkt">Wybierz Produkt:</label>
    <select name="produkt" required>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='{$row['id']}'>{$row['tytul']}</option>";
        }
        ?>
    </select>
    <br>
    <label for="ile_sztuk">Ilość Sztuk:</label>
    <input type="number" name="ile_sztuk" required>
    <br>
    <input type="submit" name="dodajProdukt" value="Dodaj Produkt">
</form>

<h2>Twój Koszyk</h2>

<?php
if (!empty($_SESSION['koszyk'])) {
    foreach ($_SESSION['koszyk'] as $produkt) {
        echo "<p>{$produkt['tytul']} - Cena za sztuke: {$produkt['cena_netto']} - Ilość: {$produkt['ile_sztuk']}</p>";
        echo "<form method='post' action='koszyk.php'>";
        echo "<input type='hidden' name='id_prod' value='{$produkt['id_prod']}'>";
        echo "<label for='nowa_ilosc'>Ilość:</label>";
        echo "<input type='number' name='ile_sztuk' value='{$produkt['ile_sztuk']}' required>";
        echo "<input type='submit' name='nowa_ilosc' value='Aktualizuj'>";
        echo "</form>";
        echo "<form method='post' action='koszyk.php'>";
        echo "<input type='hidden' name='id_produktu_do_usuniecia' value='{$produkt['id_prod']}'>";
        echo "<input type='submit' name='usunProdukt' value='Usuń Produkt'>";
        echo "</form>";
        echo "<hr>";
    }

    $calkowitaWartosc = obliczCalkowitaWartosc();
    echo "<p>Całkowita wartość koszyka: {$calkowitaWartosc} zł</p>";
} else {
    echo "<p>Twój koszyk jest pusty.</p>";
}

mysqli_close($connection);
?>
