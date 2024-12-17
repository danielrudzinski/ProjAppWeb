<?php
// Konfiguracja bazy danych

// Dane do połączenia z bazą danych
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$baza   = 'moja_strona';

// Połączenie z bazą danych
$link = mysql_connect($dbhost, $dbuser, $dbpass);

// Sprawdzenie połączenia
if (!$link) {
    echo '<b>Przerwane połączenie</b>';
    exit;
}

// Wybór bazy danych
if (!mysql_select_db($baza)) {
    echo 'Nie wybrano bazy';
    exit;
}
?>