<?php
    session_start(); // Inicjalizacja sesji

    // Zmienne globalne
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'] ?? '';
        $group = $_POST['group'] ?? '';
        $_SESSION['name'] = $name;
        $_SESSION['group'] = $group;
    } else {
        $name = $_GET['name'] ?? '';
        $group = $_GET['group'] ?? '';
    }

    // Include i require_once
    include('dane.php');
    require_once('dane.php'); // require_once ensures the file is included only once

    // Wyświetlenie danych z zadania 1
    echo $imie.' '.$nr_indeksu.' grupa '.$nrGrupy.' <br /><br />';
    echo 'Zastosowanie metody include() <br />';

    // Warunki if, else, elseif
    if ($name) {
        echo "Cześć, $name!<br />";
    } else {
        echo "Nie podano imienia.<br />";
    }

    if ($group) {
        echo "Twoja grupa to: $group<br />";
    } elseif ($nrGrupy) {
        echo "Domyślna grupa to: $nrGrupy<br />";
    } else {
        echo "Nie podano grupy.<br />";
    }

    // Switch
    switch ($nrGrupy) {
        case 'ISI3':
            echo "Jesteś w grupie ISI3.<br />";
            break;
        default:
            echo "Nieznana grupa.<br />";
            break;
    }

    // Pętla while
    $counter = 0;
    while ($counter < 3) {
        echo "To jest iteracja pętli while: $counter<br />";
        $counter++;
    }

    // Pętla for
    for ($i = 0; $i < 3; $i++) {
        echo "To jest iteracja pętli for: $i<br />";
    }

    // Typy zmiennych $_GET, $_POST, $_SESSION
    echo '<br />Zawartość $_GET:<br />';
    print_r($_GET);
    echo '<br />Zawartość $_POST:<br />';
    print_r($_POST);
    echo '<br />Zawartość $_SESSION:<br />';
    print_r($_SESSION);
?>
