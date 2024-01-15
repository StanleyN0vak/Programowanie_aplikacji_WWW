<?php
session_start();

// Sprawdź, czy administrator jest zalogowany
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Jeśli nie jest zalogowany, przekieruj go na stronę logowania
    header('Location: admin_panel.php');
    exit();
}

$connection = mysqli_connect("localhost", "root", "", "moja_strona");

    if (!$connection){
        die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
    }

function ListaPodstron()
{
   global $connection;
   global $id_clear;
   $query="SELECT * FROM page_list WHERE id='$id_clear' ORDER BY id DESC LIMIT 100";
   $result = mysqli_query($connection, $query);

   while($row = mysqli_fetch_array($result)){
    echo $row['id'].' '.$row['tytul'].' <br />';
   }
}

ListaPodstron();

function EdytujPodstrone()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          if (isset($_POST['edytujPod'])) {
             $index = $_POST['index'];
             $tytul = $_POST['tytul'];
             $tresc = $_POST['tresc'];
             $aktywna = isset($_POST['aktywna']) ? 1 : 0;
             EdytujPodstroneDoBazy($index, $tytul, $tresc, $aktywna);
          }
       }

    $wynik = '
        <h2>Edytuj Podstrone</h2>
        <form method="post" action="admin.php">
            <label for="index">Index:</label>
            <input type="number" name="index" required>
            <br>
            <label for="tytul">Tytuł:</label>
            <input type="text" name="tytul" required>
            <br>
            <label for="tresc">Treść strony:</label>
            <textarea name="tresc" rows="4" cols="50" required></textarea>
            <br>
            <label for="aktywna">Aktywna:</label>
            <input type="checkbox" name="aktywna" value="1">
            <br>
            <input type="submit" name="edytujPod" value="Zapisz">
        </form>
    ';

    echo $wynik;
}

function EdytujPodstroneDoBazy($index, $tytul, $tresc, $aktywna)
{
       global $connection;
       $query = "UPDATE page_list SET page_title='$tytul', page_content='$tresc', status='$aktywna' WHERE id='$index'";

       $result = mysqli_query($connection, $query);

       if ($result) {
          echo "Podstrona została edytowana pomyślnie.";
       } else {
          echo "Błąd podczas edytowania podstrony: " . mysqli_error($connection);
       }

       //mysqli_close($connection);
}

EdytujPodstrone();

function DodajNowaPodstrone()
{
   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (isset($_POST['dodajPod'])) {
         $tytul = $_POST['tytul'];
         $tresc = $_POST['tresc'];
         $aktywna = isset($_POST['aktywna']) ? 1 : 0;
         DodajPodstroneDoBazy($tytul, $tresc, $aktywna);
      }
   }

   // Formularz dodawania nowej podstrony
   echo '
   <h2>Dodaj Nową Podstronę</h2>
   <form method="post" action="">
      <label for="tytul">Tytuł:</label>
      <input type="text" name="tytul" required>
      <br>
      <label for="tresc">Treść strony:</label>
      <textarea name="tresc" rows="4" cols="50" required></textarea>
      <br>
      <label for="aktywna">Aktywna:</label>
      <input type="checkbox" name="aktywna" value="1">
      <br>
      <input type="submit" name="dodajPod" value="Dodaj">
   </form>
   ';
}

function DodajPodstroneDoBazy($tytul, $tresc, $aktywna)
{
   global $connection;
   $query = "INSERT INTO page_list (page_title, page_content, status) VALUES ('$tytul', '$tresc', '$aktywna')";

   $result = mysqli_query($connection, $query);

   if ($result) {
      echo "Podstrona została dodana pomyślnie.";
   } else {
      echo "Błąd podczas dodawania podstrony: " . mysqli_error($connection);
   }

   //mysqli_close($connection);
}

DodajNowaPodstrone();

function UsunPodstroneForm()
{
   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (isset($_POST['usunPod'])) {
         $id = $_POST['id'];
         UsunPodstrone($id);
      }
   }

   // Formularz dodawania nowej podstrony
   echo '
   <h2>Usuń Podstronę</h2>
   <form method="post" action="">
      <label for="tytul">ID:</label>
      <input type="text" name="id" required>
      <br>
      <input type="submit" name="usunPod" value="Usuń">
   </form>
   ';
}

function UsunPodstrone($id)
{
    global $connection;
    $id = mysqli_real_escape_string($connection, $id);

    $query = "DELETE FROM page_list WHERE id = '$id' LIMIT 1";

    $result = mysqli_query($connection, $query);

    if ($result) {
        echo 'Podstrona została pomyślnie usunięta.';
    } else {
        echo 'Błąd podczas usuwania podstrony: ' . mysqli_error($connection);
    }

    //mysqli_close($connection);
}

UsunPodstroneForm();

function DodajKategorie()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          if (isset($_POST['dodajKat'])) {
             $nazwa = $_POST['nazwa'];
             $matka = $_POST['matka'];
             $aktywna = isset($_POST['aktywna']) ? 1 : 0;
             DodajKategorieDoBazy($nazwa, $matka, $aktywna);
          }
       }

       // Formularz dodawania nowej kategorii
          echo '
          <h2>Dodaj Nową Kategorię</h2>
          <form method="post" action="">
             <label for="nazwa">Nazwa:</label>
             <input type="text" name="nazwa" required>
             <br>
             <label for="matka">Matka Kategorii:</label>
             <input type="number" name="matka" required>
             <br>
             <label for="aktywna">Aktywna:</label>
             <input type="checkbox" name="aktywna" value="1">
             <br>
             <input type="submit" name="dodajKat" value="Dodaj">
          </form>
          ';
}

function DodajKategorieDoBazy($nazwa, $matka, $aktywna)
{
   global $connection;
   $query = "INSERT INTO kategorie (nazwa, matka, status) VALUES ('$nazwa', '$matka', '$aktywna')";

   $result = mysqli_query($connection, $query);

   if ($result) {
      echo "Kategoria została dodana pomyślnie.";
   } else {
      echo "Błąd podczas dodawania kategorii: " . mysqli_error($connection);
   }

   //mysqli_close($connection);
}

DodajKategorie();

function EdytujKategorie()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          if (isset($_POST['edytujKat'])) {
             $index = $_POST['index'];
             $nazwa = $_POST['nazwa'];
             $matka = $_POST['matka'];
             $aktywna = isset($_POST['aktywna']) ? 1 : 0;
             EdytujKategorieDoBazy($index, $nazwa, $matka, $aktywna);
          }
       }

    $wynik = '
        <h2>Edytuj Kategorie</h2>
        <form method="post" action="admin.php">
            <label for="index">Index:</label>
            <input type="number" name="index" required>
            <br>
            <label for="nazwa">Nazwa:</label>
            <input type="text" name="nazwa">
            <br>
            <label for="matka">Matka:</label>
            <input type="number" name="matka">
            <br>
            <label for="aktywna">Aktywna:</label>
            <input type="checkbox" name="aktywna" value="1">
            <br>
            <input type="submit" name="edytujKat" value="Zapisz">
        </form>
    ';

    echo $wynik;
}

function EdytujKategorieDoBazy($index, $nazwa, $matka, $aktywna)
{
       global $connection;
       $query = "UPDATE kategorie SET nazwa='$nazwa', matka='$matka', status='$aktywna' WHERE id='$index'";

       $result = mysqli_query($connection, $query);

       if ($result) {
          echo "Kategoria została edytowana pomyślnie.";
       } else {
          echo "Błąd podczas edytowania kategorii: " . mysqli_error($connection);
       }

       //mysqli_close($connection);
}

EdytujKategorie();

function UsunKategorieForm()
{
   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (isset($_POST['usunKat'])) {
         $id = $_POST['id'];
         UsunKategorie($id);
      }
   }

   // Formularz usuwania Kategorii
   echo '
   <h2>Usuń Kategorie</h2>
   <form method="post" action="">
      <label for="id">ID:</label>
      <input type="number" name="id" required>
      <br>
      <input type="submit" name="usunKat" value="Usuń">
   </form>
   ';
}

function UsunKategorie($id)
{
    global $connection;
    $id = mysqli_real_escape_string($connection, $id);

    $query = "DELETE FROM kategorie WHERE id = '$id' LIMIT 1";

    $result = mysqli_query($connection, $query);

    if ($result) {
        echo 'Kategoria została pomyślnie usunięta.';
    } else {
        echo 'Błąd podczas usuwania kategorii: ' . mysqli_error($connection);
    }

    //mysqli_close($connection);
}

UsunKategorieForm();

function PokazKategorie()
{
    global $connection;
    $query = "SELECT * FROM kategorie";
    $result = mysqli_query($connection, $query);

    echo "<h2>Lista Kategorii:</h2>";
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "ID: {$row['id']} | Nazwa: {$row['nazwa']} | Matka: {$row['matka']}<br>";
        }
    } else {
        echo "Brak kategorii do wyświetlenia. <br>";
    }

    //mysqli_close($connection);
}

PokazKategorie();

// Pobierz kategorie z bazy danych
$querySelectCategories = "SELECT * FROM kategorie";
$resultCategories = mysqli_query($connection, $querySelectCategories);

// Generuj drzewo kategorii
$categoriesTree = array();

while ($row = mysqli_fetch_assoc($resultCategories)) {
    $categoriesTree[$row['matka']][] = $row;
}

function generateCategoryTree($categories, $parentId = 0, $depth = 0) {
    if (isset($categories[$parentId])) {
        foreach ($categories[$parentId] as $category) {
            echo str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $depth) . '>' . '&nbsp' . $category['nazwa'] . "<br>";
            generateCategoryTree($categories, $category['id'], $depth + 1);
        }
    }
}

generateCategoryTree($categoriesTree);

function DodajProdukty()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          if (isset($_POST['dodajPro'])) {
             $tytul = $_POST['tytul'];
             $opis = $_POST['opis'];
             $data_utworzenia = $_POST['data_utworzenia'];
             $data_modyfikacji = $_POST['data_modyfikacji'];
             $data_wygasniecia = $_POST['data_wygasniecia'];
             $cena_netto = $_POST['cena_netto'];
             $podatek_vat = $_POST['podatek_vat'];
             $ilosc_dostepnych_sztuk_w_magazynie = $_POST['ilosc_dostepnych_sztuk_w_magazynie'];
             $status_dostepnosci = $_POST['status_dostepnosci'];
             $kategoria = $_POST['kategoria'];
             $gabaryt_produktu = $_POST['gabaryt_produktu'];
             $zdjecie = $_POST['zdjecie'];
             DodajProduktDoBazy($tytul, $opis, $data_utworzenia, $data_modyfikacji,
             $data_wygasniecia, $cena_netto, $podatek_vat, $ilosc_dostepnych_sztuk_w_magazynie, $status_dostepnosci,
             $kategoria, $gabaryt_produktu, $zdjecie);
          }
       }

       // Formularz dodawania nowego produktu
          echo '
          <h2>Dodaj Nowy Produkt</h2>
          <form method="post" action="">
             <label for="tytul">Tytuł:</label>
             <input type="text" name="tytul" required>
             <br>
             <label for="opis">Opis:</label>
             <input type="textarea" name="opis" required>
             <br>
             <label for="data_utworzenia">Data Utworzenia:</label>
             <input type="date" name="data_utworzenia">
             <br>
             <label for="data_modyfikacji">Data Modyfikacji:</label>
             <input type="date" name="data_modyfikacji">
             <br>
             <label for="data_wygasniecia">Data Wygaśnięcia:</label>
             <input type="date" name="data_wygasniecia">
             <br>
             <label for="cena_netto">Cena Netto:</label>
             <input type="number" name="cena_netto" required>
             <br>
             <label for="podatek_vat">Podatek Vat:</label>
             <input type="number" name="podatek_vat" required>
             <br>
             <label for="ilosc_dostepnych_sztuk_w_magazynie">Ilość Dostępnych Sztuk w Magazynie:</label>
             <input type="number" name="ilosc_dostepnych_sztuk_w_magazynie" required>
             <br>
             <label for="status_dostepnosci">Status Dostępności:</label>
             <input type="checkbox" name="status_dostepnosci" value="1" required>
             <br>
             <label for="kategoria">Kategoria ID:</label>
             <input type="number" name="kategoria" required>
             <br>
             <label for="gabaryt_produktu">Gabaryt Produktu:</label>
             <input type="number" name="gabaryt_produktu" required>
             <br>
             <label for="zdjecie">Zdjęcie:</label>
             <input type="file" name="zdjecie" accept="image/png, image/jpeg" required>
             <br>
             <input type="submit" name="dodajPro" value="Dodaj">
          </form>
          ';
}

function DodajProduktDoBazy($tytul, $opis, $data_utworzenia, $data_modyfikacji, $data_wygasniecia, $cena_netto, $podatek_vat,
    $ilosc_dostepnych_sztuk_w_magazynie, $status_dostepnosci, $kategoria, $gabaryt_produktu, $zdjecie)
{
   global $connection;
   $query = "INSERT INTO produkty (tytul, opis, data_utworzenia, data_modyfikacji, data_wygasniecia, cena_netto, podatek_vat, ilosc_dostepnych_sztuk_w_magazynie,
   status_dostepnosci, kategoria, gabaryt_produktu, zdjecie) VALUES ('$tytul', '$opis', '$data_utworzenia', '$data_modyfikacji',
   '$data_wygasniecia', '$cena_netto', '$podatek_vat', '$ilosc_dostepnych_sztuk_w_magazynie', '$status_dostepnosci', '$kategoria', '$gabaryt_produktu', '$zdjecie')";

   $result = mysqli_query($connection, $query);

   if ($result) {
      echo "Produkt został dodany pomyślnie.";
   } else {
      echo "Błąd podczas dodawania kategorii: " . mysqli_error($connection);
   }
}

DodajProdukty();

function UsunProduktForm()
{
   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (isset($_POST['usunPro'])) {
         $id = $_POST['id'];
         UsunProdukt($id);
      }
   }

   // Formularz usuwania Produktu
   echo '
   <h2>Usuń Produkt</h2>
   <form method="post" action="">
      <label for="id">ID:</label>
      <input type="number" name="id" required>
      <br>
      <input type="submit" name="usunPro" value="Usuń">
   </form>
   ';
}

function UsunProdukt($id)
{
    global $connection;
    $id = mysqli_real_escape_string($connection, $id);

    $query = "DELETE FROM produkty WHERE id = '$id' LIMIT 1";

    $result = mysqli_query($connection, $query);

    if ($result) {
        echo 'Produkt został pomyślnie usunięty.';
    } else {
        echo 'Błąd podczas usuwania produktu: ' . mysqli_error($connection);
    }
}

UsunProduktForm();

function EdytujProdukt()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          if (isset($_POST['edytujPro'])) {
               $id = $_POST['id'];
               $tytul = $_POST['tytul'];
               $opis = $_POST['opis'];
               $data_modyfikacji = $_POST['data_modyfikacji'];
               $data_wygasniecia = $_POST['data_wygasniecia'];
               $cena_netto = $_POST['cena_netto'];
               $podatek_vat = $_POST['podatek_vat'];
               $ilosc_dostepnych_sztuk_w_magazynie = $_POST['ilosc_dostepnych_sztuk_w_magazynie'];
               $status_dostepnosci = $_POST['status_dostepnosci'];
               $kategoria = $_POST['kategoria'];
               $gabaryt_produktu = $_POST['gabaryt_produktu'];
               $zdjecie = $_POST['zdjecie'];
               EdytujProduktDoBazy($id, $tytul, $opis, $data_modyfikacji,
               $data_wygasniecia, $cena_netto, $podatek_vat,
               $ilosc_dostepnych_sztuk_w_magazynie, $status_dostepnosci,
               $kategoria, $gabaryt_produktu, $zdjecie);
          }
       }

    $wynik = '
        <h2>Edytuj Produkt</h2>
        <form method="post" action="">
           <label for="id">ID:</label>
           <input type="number" name="id" required>
           <br>
           <label for="tytul">Tytuł:</label>
           <input type="text" name="tytul" required>
           <br>
           <label for="opis">Opis:</label>
           <input type="textarea" name="opis" required>
           <br>
           <label for="data_modyfikacji">Data Modyfikacji:</label>
           <input type="date" name="data_modyfikacji">
           <br>
           <label for="data_wygasniecia">Data Wygaśnięcia:</label>
           <input type="date" name="data_wygasniecia">
           <br>
           <label for="cena_netto">Cena Netto:</label>
           <input type="number" name="cena_netto" required>
           <br>
           <label for="podatek_vat">Podatek Vat:</label>
           <input type="number" name="podatek_vat" required>
           <br>
           <label for="ilosc_dostepnych_sztuk_w_magazynie">Ilość Dostępnych Sztuk w Magazynie:</label>
           <input type="number" name="ilosc_dostepnych_sztuk_w_magazynie" required>
           <br>
           <label for="status_dostepnosci">Status Dostępności:</label>
           <input type="checkbox" name="status_dostepnosci" value="1" required>
           <br>
           <label for="kategoria">Kategoria ID:</label>
           <input type="number" name="kategoria" required>
           <br>
           <label for="gabaryt_produktu">Gabaryt Produktu:</label>
           <input type="number" name="gabaryt_produktu" required>
           <br>
           <label for="zdjecie">Zdjęcie:</label>
           <input type="file" name="zdjecie" accept="image/png, image/jpeg" required>
           <br>
           <input type="submit" name="edytujPro" value="Dodaj">
        </form>
    ';

    echo $wynik;
}

function EdytujProduktDoBazy($id, $tytul, $opis, $data_modyfikacji, $data_wygasniecia, $cena_netto, $podatek_vat,
                                   $ilosc_dostepnych_sztuk_w_magazynie, $status_dostepnosci, $kategoria, $gabaryt_produktu, $zdjecie)
{
       global $connection;
       $query = "UPDATE produkty SET tytul='$tytul', opis='$opis', data_modyfikacji='$data_modyfikacji', data_wygasniecia='$data_wygasniecia',
       cena_netto='$cena_netto', podatek_vat='$podatek_vat', ilosc_dostepnych_sztuk_w_magazynie='$ilosc_dostepnych_sztuk_w_magazynie',
       status_dostepnosci='$status_dostepnosci', kategoria='$kategoria', gabaryt_produktu='$gabaryt_produktu',
       zdjecie='$zdjecie' WHERE id='$id'";

       $result = mysqli_query($connection, $query);

       if ($result) {
          echo "Produkt został edytowany pomyślnie.";
       } else {
          echo "Błąd podczas edytowania produktu: " . mysqli_error($connection);
       }
}

EdytujProdukt();

function PokazProdukty()
{
    global $connection;
    $query = "SELECT * FROM produkty";
    $result = mysqli_query($connection, $query);

    echo "<h2>Lista Produktów:</h2>";
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "ID: {$row['id']} | Tytuł: {$row['tytul']} | Data utworzenia: {$row['data_utworzenia']} | Data modyfikacji: {$row['data_modyfikacji']}
            | Data wygasniecia: {$row['data_wygasniecia']} | Cena Netto: {$row['cena_netto']} | Dostępne sztuki: {$row['ilosc_dostepnych_sztuk_w_magazynie']}
            | Status: {$row['status_dostepnosci']} | Kategoria: {$row['kategoria']} | Gabaryt: {$row['gabaryt_produktu']}kg <br>";
        }
    } else {
        echo "Brak produktów do wyświetlenia. <br>";
    }
}

PokazProdukty();

mysqli_close($connection);
?>
<style>
<?php include '../css/adminStyle.css'; ?>
</style>