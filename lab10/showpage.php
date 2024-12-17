<?php
// showpage.php - Wyświetlanie podstron

function PokazPodstrone($id) {
    // Zabezpieczenie danych wejściowych
    $id_clear = htmlspecialchars($id);

    // Zapytanie SQL z limitem
    $query = "SELECT * FROM page_list WHERE id='$id_clear' LIMIT 1";
    $result = mysql_query($query);

    // Pobranie wyniku zapytania
    $row = mysql_fetch_array($result);

    // Sprawdzenie, czy strona istnieje
    if (empty($row['id'])) {
        $web = '[nie_znaleziono_strony]';
    } else {
        $web = $row['page_content'];
    }

    return $web;
}
?>