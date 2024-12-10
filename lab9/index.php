<?php
// index.php - Główna logika strony

// Wyłączenie wyświetlania niektórych błędów
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

// Tablica stron dostępnych w serwisie
$pages = [
    'home'             => 'glowna.php',
    'CODA'             => 'html/CODA.html',
    'greenbook'        => 'html/greenbook.html',
    'NOMADLAND'        => 'html/NOMADLAND.html',
    'Oppenheimer'      => 'html/Oppenheimer.html',
    'parasite'         => 'html/parasite.html',
    'changebackground' => 'html/changebackground.html',
    'kontakt'          => 'html/kontakt.html',
    'filmy'            => 'html/filmy.html'
];

// Pobranie strony z parametru GET i zabezpieczenie przed atakami
$page = isset($_GET['page']) ? htmlspecialchars($_GET['page']) : 'home';
$file_to_include = isset($pages[$page]) ? $pages[$page] : $pages['home'];

// Sprawdzenie, czy plik istnieje
if (file_exists($file_to_include)) {
    ob_start();
    include $file_to_include;
    $content = ob_get_clean();
} else {
    $content = "<p>Page not found.</p>";
}

// Wczytanie szablonu strony
$template = file_get_contents('html/template.html');

// Wstawienie treści do szablonu
$output = str_replace('{{content}}', $content, $template);

// Wyświetlenie strony
echo $output;
?>