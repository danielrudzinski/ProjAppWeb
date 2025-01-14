<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$db = 'moja_strona';
$login = 'root';
$pass = 'rootpass';

$link = new mysqli($dbhost,$dbuser,$dbpass,$db) or die("Połączenie nieudane: $s\n". $link -> error);

if (!$link) echo '<b>przerwane połączenie</b>';
if(!mysqli_select_db($link, $db)) echo 'nie wybrano bazy';
?>