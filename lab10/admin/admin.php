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
// Dodać Edytuj, Usuń i Pokaż

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
      <input type="text" name="id" required>
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
mysqli_close($connection);
?>
<style>
<?php include '../css/adminStyle.css'; ?>
</style>