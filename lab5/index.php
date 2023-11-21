<?php
    error_reporting(E_ALL^E_NOTICE^E_WARNING);

    $historia = "./html/Historia.html";
    $karty = "./html/Karty_graficzne.html";
    $wirtualna = "./html/Wirtualna_rzeczywistosc.html";
    $rodzaje = "./html/Rodzaje_Ramu.html";
    $galeria = "./html/Galeria.html";
    $video = "./html/video.html";
    /*
    if (file_exists($historia)) {
        echo "The file $historia exists";
    } else {
        echo "The file $historia does not exists";
    }
    */

    if($_GET['idp']=='')$strona='./html/glowna.html';
    if($_GET['idp']=='Historia')$strona='./html/Historia.html';
    if($_GET['idp']=='Karty_graficzne')$strona='./html/Karty_graficzne.html';
    if($_GET['idp']=='Wirtualna_rzeczywistosc')$strona='./html/Wirtualna_rzeczywistość.html';
    if($_GET['idp']=='Rodzaje_Ramu')$strona='./html/Rodzaje_Ramu.html';
    if($_GET['idp']=='Galeria')$strona='./html/Galeria.html';
    if($_GET['idp']=='js')$strona='./html/js.html';
    if($_GET['idp']=='video')$strona='./html/video.html';
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
        <li><a href="index.php?idp=Historia">Historia</a></li>
        <li><a href="index.php?idp=Rodzaje_Ramu">Rodzaje Ramu</a></li>
        <li><a href="index.php?idp=Wirtualna_rzeczywistosc">Wirtualna rzeczywistość</a></li>
        <li><a href="index.php?idp=Karty_graficzne">Karty Graficzne</a></li>
        <li><a href="index.php?idp=Galeria">Galeria</a></li>
        <li><a href="index.php?idp=js">Skrypty</a></li>
        <li><a href="index.php?idp=video">Video</a></li>
    </ul>
</div>
<div class="php">
	<?php
	include($strona);
	    $nr_indeksu = "166329";
	    $nrGrupy = '4';
	    echo "Autor: Mateusz Szymański" .$nr_indeksu. " grupa " .$nrGrupy. "<br><br>";
	?>
</div>
</body>
</html>