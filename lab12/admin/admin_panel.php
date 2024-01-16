<?php
session_start();

// Sprawdź, czy formularz logowania został przesłany
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];

    // Sprawdź, czy dane logowania są poprawne
    if ($login == 'admin' && $password == 'admin123') {
        // Ustaw flagę zalogowanego administratora w sesji
        $_SESSION['admin_logged_in'] = true;

        // Przekieruj do panelu admina lub wyświetl komunikat o sukcesie
        header('Location: ./admin/admin.php');
        exit();
    } else {
        // Wyświetl komunikat o błędnych danych logowania
        echo 'Błędne dane logowania';
    }
}
?>
<div class="logowanie">
    <h1 class="heading">Panel CMS:</h1>
    <div class="logowanie">
        <!-- Formularz logowania -->
        <form method="post" action="">
            <label>Login:</label>
            <input type="text" name="login" required>
            <br>
            <label>Hasło:</label>
            <input type="password" name="password" required>
            <br>
            <input type="submit" value="Zaloguj">
        </form>
    </div>
</div>