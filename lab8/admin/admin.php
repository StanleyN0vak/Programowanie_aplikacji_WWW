<?php
function FormularzLogowania()
{
   $wynik ='
   <div class="logowanie">
    <h1 class="heading">Panel CMS:</h1>
    <div class="logowanie">
        <form method="post" name="LoginForm" enctype="multipart/form_data" action="'.$_SERVER['REQUEST_URI'].'">
            <table class="logowanie">
                <tr><td class="log4_t">[email]</td><td><input type="text" name="login_email" class="logowanie" /></td></tr>
                <tr><td class="log4_t">[haslo]</td><td><input type="password" name="login_pass" class="logowanie" /></td></tr>
                <tr><td>&nbsp;</td><td><input type="submit" name="x1_submit" class="logowanie" value="zaloguj" /></td></tr>
            </table>
        </form>
    </div>
   </div>
   ';

   echo $wynik;
}

FormularzLogowania();

function ListaPodstron()
{
   global $id_clear;
   $connection = mysqli_connect("localhost", "root", "", "moja_strona");
   $query="SELECT * FROM page_list WHERE id='$id_clear' ORDER BY id DESC LIMIT 100";
   $result = mysqli_query($connection, $query);

   while($row = mysqli_fetch_array($result)){
    echo $row['id'].' '.$row['tytul'].' <br />';
   }
}

ListaPodstron();

function EdytujPodstrone()
{
    $wynik = '
        <h2>Edytuj Podstrone</h2>
        <form method="post" action="admin.php">
            <label for="tytul">Tytuł:</label>
            <input type="text" name="tytul" required>
            <br>
            <label for="tresc">Treść strony:</label>
            <textarea name="tresc" rows="4" cols="50" required></textarea>
            <br>
            <label for="aktywna">Aktywna:</label>
            <input type="checkbox" name="aktywna" value="1">
            <br>
            <input type="submit" value="Zapisz">
        </form>
    ';

    echo $wynik;
}

EdytujPodstrone();

function DodajNowaPodstrone()
{
   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (isset($_POST['submit'])) {
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
      <input type="submit" name="submit" value="Dodaj">
   </form>
   ';
}

function DodajPodstroneDoBazy($tytul, $tresc, $aktywna)
{
   $connection = mysqli_connect("localhost", "root", "", "moja_strona");

   if (!$connection) {
      die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
   }

   $query = "INSERT INTO page_list (page_title, page_content, status) VALUES ('$tytul', '$tresc', '$aktywna')";

   $result = mysqli_query($connection, $query);

   if ($result) {
      echo "Podstrona została dodana pomyślnie.";
   } else {
      echo "Błąd podczas dodawania podstrony: " . mysqli_error($connection);
   }

   mysqli_close($connection);
}

DodajNowaPodstrone();

function UsunPodstroneForm()
{
   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (isset($_POST['submit'])) {
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
      <input type="submit" name="submit" value="Usuń">
   </form>
   ';
}

function UsunPodstrone($id)
{
    $link = mysqli_connect("localhost", "root", "", "moja_strona");

    if (!$link) {
        die('<b>Przerwane połączenie</b>: ' . mysqli_connect_error());
    }

    $id = mysqli_real_escape_string($link, $id);

    $query = "DELETE FROM page_list WHERE id = '$id' LIMIT 1";

    $result = mysqli_query($link, $query);

    if ($result) {
        echo 'Podstrona została pomyślnie usunięta.';
    } else {
        echo 'Błąd podczas usuwania podstrony: ' . mysqli_error($link);
    }

    mysqli_close($link);
}

UsunPodstroneForm();
?>