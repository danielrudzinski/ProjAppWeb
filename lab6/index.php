<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

$pages = [
    'home' => 'glowna.php',
    'CODA' => 'html/CODA.html',
    'greenbook' => 'html/greenbook.html',
    'NOMADLAND' => 'html/NOMADLAND.html',
    'Oppenheimer' => 'html/Oppenheimer.html',
    'parasite' => 'html/parasite.html',
    'changebackground' => 'html/changebackground.html',
    'kontakt' => 'html/kontakt.html',
    'filmy'=> 'html/filmy.html'
];

$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$file_to_include = isset($pages[$page]) ? $pages[$page] : $pages['home'];

if (file_exists($file_to_include)) {
    ob_start(); 
    include $file_to_include; 
    $content = ob_get_clean(); 
} else {
    $content = "<p>Page not found.</p>";
}

$template = file_get_contents('html/template.html');

$output = str_replace('{{content}}', $content, $template);

echo $output;
?>