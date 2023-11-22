<?php
function PokazPodstrone($id)
{
    $id_clear = htmlspecialchars($id);

    $link = mysqli_connect('localhost', 'root', '', 'moja_strona');

    if (!$link) {
        die('<b>Przerwane połączenie</b>: ' . mysqli_connect_error());
    }

    $query = "SELECT * FROM page_list WHERE id = '$id_clear' LIMIT 1";
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);

    if (empty($row['id'])) {
        $web = '[nie_znaleziono_strony]';
    } else {
        $web = $row['page_content'];
    }

    mysqli_close($link);

    return $web;
}
?>
