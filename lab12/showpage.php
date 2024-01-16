<?php
include("cfg.php");

function PokazPodstrone($id)
{
    global $link;

    // Użyj prepared statement, aby uniknąć SQL injection
    $id_clear = mysqli_real_escape_string($link, $id);

    $query = "SELECT * FROM page_list WHERE id = ? LIMIT 1";

    // Użyj prepared statement do bezpiecznego wykonania zapytania SQL
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "s", $id_clear);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);

    // Zamknij połączenie po uzyskaniu wyniku
    mysqli_stmt_close($stmt);

    if (empty($row['id'])) {
        $web = '[nie_znaleziono_strony]';
    } else {
        $web = $row['page_content'];
    }

    return $web;
}

function PobierzListeStron()
{
    global $link;

    $query = "SELECT id, page_title FROM page_list WHERE status = 0";

    $result = mysqli_query($link, $query);
    $pages = mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_free_result($result);

    return $pages;
}
?>
