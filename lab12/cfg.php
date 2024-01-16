<?php
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = '';
    $baza = 'moja_strona';
    $loginA = "admin";
    $passA = "admin123";

    //Połączenie z bazą danych
    $link = mysqli_connect($dbhost, $dbuser, $dbpass, $baza);

    if (!$link) {
        die('<b>Przerwane połączenie</b>: ' . mysqli_connect_error());
    }

    //echo 'Połączenie udane!';
    //mysqli_close($link);
?>
