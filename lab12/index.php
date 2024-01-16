<?php
    // Wyłącz raportowanie błędów związanych z NOTICE i WARNING
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

    // Dołącz plik konfiguracyjny
    include("cfg.php");

    // Definicje ścieżek do plików
    $strony = [
        'glowna' => '1',
        'Historia' => '2',
        'Karty_graficzne' => '4',
        'Wirtualna_rzeczywistosc' => '7',
        'Rodzaje_Ramu' => '5',
        'Galeria' => '8',
        'js' => '3',
        'video' => '6',
        'koszyk' => 'koszyk.php',
        'admin' => './admin/admin_panel.php'
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
        // Generuj dynamiczne menu na podstawie zawartości bazy danych
        include("showpage.php");
        $pages = PobierzListeStron();

        foreach ($pages as $page) {
                $id = htmlspecialchars($page['id']);
                $title = ucfirst(str_replace('_', ' ', pathinfo($page['page_title'], PATHINFO_FILENAME)));
                echo '<li><a href="index.php?idp=' . $id . '">' . $title . '</a></li>';
                }
        $adminPath = $strony['admin'];
        $koszykPath = $strony['koszyk'];
        echo '<li><a href="' . $adminPath . '">Admin Panel</a></li>';
        echo '<li><a href="' . $koszykPath . '">Koszyk</a></li>';
        ?>
    </ul>
</div>
<div class="php">
	<?php
	// Sprawdź, czy 'idp' zostało ustawione w adresie URL
    $stronaId = isset($_GET['idp']) ? $_GET['idp'] : 'glowna';

   	// Dołącz zawartość strony z bazy danych
   	$stronaContent = PokazPodstrone($stronaId);
   	echo $stronaContent;

	// Bezpieczne wyświetlanie informacji
	$nr_indeksu = "166329";
	$nrGrupy = '4';
	echo "Autor: Mateusz Szymański " . htmlspecialchars($nr_indeksu) . " grupa " . htmlspecialchars($nrGrupy) . "<br><br>";
	?>
</div>
</body>
</html>
