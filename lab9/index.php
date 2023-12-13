<?php
    // Wyłącz raportowanie błędów związanych z NOTICE i WARNING
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

    // Dołącz plik konfiguracyjny
    include("cfg.php");

    // Definicje ścieżek do plików
    $strony = [
        'glowna' => './html/glowna.html',
        'Historia' => './html/Historia.html',
        'Karty_graficzne' => './html/Karty_graficzne.html',
        'Wirtualna_rzeczywistosc' => './html/Wirtualna_rzeczywistość.html',
        'Rodzaje_Ramu' => './html/Rodzaje_Ramu.html',
        'Galeria' => './html/Galeria.html',
        'js' => './html/js.html',
        'video' => './html/video.html'
    ];

    // Domyślna strona, jeśli 'idp' nie jest ustawione lub nieznane
    $strona = isset($_GET['idp']) && isset($strony[$_GET['idp']]) ? $strony[$_GET['idp']] : $strony['glowna'];
?>

<!DOCTYPE html>
<html lang="PL">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
	<meta http-equiv="Content-Language" content="pl" />
	<meta name="Author" content="Mateusz Szymański" />
	<link rel="stylesheet" href="css/myStyle.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<title>Komputer moją pasją</title>
</head>
<body>
<div class="menu">
    <ul>
        <?php
        // Generuj dynamiczne menu
        foreach ($strony as $id => $sciezka) {
            echo '<li><a href="index.php?idp=' . htmlspecialchars($id) . '">' . ucfirst(str_replace('_', ' ', pathinfo($sciezka, PATHINFO_FILENAME))) . '</a></li>';
        }
        ?>
    </ul>
</div>
<div class="php">
	<?php
	// Dołącz zawartość strony
	include($strona);

	// Bezpieczne wyświetlanie informacji
	$nr_indeksu = "166329";
	$nrGrupy = '4';
	echo "Autor: Mateusz Szymański " . htmlspecialchars($nr_indeksu) . " grupa " . htmlspecialchars($nrGrupy) . "<br><br>";
	?>
</div>
</body>
</html>
