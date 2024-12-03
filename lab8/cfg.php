<?
    $dsbhost='localhost';
    $dbuser='root';
    $dbpass='';
    $baza='moja_strona';
    $login = 'admin';
    $pass = 'password';
    $link=mysql_connect($dbhost,$dbuser,$dbpass);
    if(!$link) echo '<b>przerwane połączenie<b>';
    if(!mysql_select_db($baza)) echo 'nie wybrano bazy';
    ?>