<?php
ob_start();
//----------STYL-------------

echo '<style>';
include('css/admin_style.css');
echo '</style>';




// ----------FUNKCJE------------

// Formularz logowania
function FormularzLogowania()
{
    $wynik = '
    <div class="logowanie">
        <h1 class="heading">Panel CMS:</h1>
        <div class="logowanie">
            <form method="post" name"LoginForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URI'].'">
                <table class="logowanie">
                    <tr><td class="log4_t">[email]</td><td><input type="text" name="login_email" class="logowanie" /></td></tr>
                    <tr><td class="log4_t">[haslo]</td><td><input type="password" name="login_pass" class="logowanie" /></td></tr>
                    <tr><td>&nbsp;</td><td><input type="submit" name="x1_submit" class="logowanie" value="Zaloguj" /></td></tr>
                </table>
            </form>
        </div>
    </div>
    ';
    return $wynik;
}

// Lista podstron
function ListaPodstron()
{
    global $link;

    if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
        $idToDelete = $_GET['id'];
        UsunPodstrone($idToDelete);

		// Po usunięciu przekierowywanie na stronę z listą podstron
        header('Location: admin.php');
        exit();
    }

	// Pobieranie danych o podstronach z bazy danych
    $query = "SELECT * FROM page_list LIMIT 100";
    $result = mysqli_query($link, $query);

	// Wyświetlanie listy podstron
    echo '<h1>Lista Podstron</h1>';
    echo '<table border="1">
            <tr>
                <th>ID</th>
                <th>Tytuł Podstrony</th>
                <th>Wykonaj</th>
            </tr>';
    while ($row = mysqli_fetch_array($result)) {
        echo '<tr>';
        echo '<td>' . $row['id'] . '</td>';
        echo '<td>' . $row['page_title'] . '</td>';
        echo '<td><a href="?action=edit&id=' . $row['id'] . '">Edytuj</a> | 
              <a href="?action=delete&id=' . $row['id'] . '" onclick="return confirm(\'Czy na pewno chcesz usunąć tę podstronę?\')">Usuń</a></td>';
        echo '</tr>';
    }

    echo '</table>';
}

// Edytuj podstronę
function EdytujPodstrone()
{
    global $link;
    if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
        $id = $_GET['id'];

		// Pobieranie danych o wybranej podstronie
        $query = "SELECT * FROM page_list WHERE id = $id LIMIT 1";
        $result = mysqli_query($link, $query);
        $row = mysqli_fetch_assoc($result);

        // Wyświetlanie formularza edycji podstrony
        echo '<h1>Edytuj Podstronę</h1>';
        echo '<form method="post" action="?action=update&id=' . $id . '">
                <input type="hidden" name="edit_id" value="' . $id . '">
                <label>Tytuł Podstrony:</label>
                <input type="text" name="edit_title" value="' . $row['page_title'] . '" required>
                <label>Treść Strony:</label>
                <textarea name="edit_content">' . $row['page_content'] . '</textarea>
                <label>Aktywna:</label>
                <input type="checkbox" name="edit_active" ' . ($row['status'] ? 'checked' : '') . '>
                <input type="submit" name="edit_submit" value="Zapisz zmiany">
              </form>';
    }
}

// Dodaj nową podstronę
function DodajNowaPodstrone()
{
    global $link;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_submit'])) {
        // Zabezpiecz dane wejściowe
        $add_title = mysqli_real_escape_string($link, $_POST['add_title']);
        $add_content = mysqli_real_escape_string($link, $_POST['add_content']);
        $add_active = isset($_POST['add_active']) ? 1 : 0;

        // Zapytanie SQL do dodania nowej podstrony
        $insert_query = "INSERT INTO page_list (page_title, page_content, status) VALUES ('$add_title', '$add_content', $add_active)";

        if (mysqli_query($link, $insert_query)) {
            // Po dodaniu przekierowuje na stronę z listą podstron
            header('Location: admin.php');
            exit();  // Zakończ skrypt, aby nie wyświetlać żadnego dalszego kodu
        } else {
            // Wyświetl błąd, jeśli zapytanie się nie powiedzie
            echo "Błąd: " . mysqli_error($link);
        }
    }

    // Wyświetlanie formularza dodawania nowej podstrony
    // Pamiętaj, że kod HTML powinien pojawić się po przetworzeniu POST
    echo '<h3>Dodaj Nową Podstronę</h3>';
    echo '<form method="post" action="?action=add">
            <label>Tytuł Podstrony:</label>
            <input type="text" name="add_title" required>
            <label>Treść Strony:</label>
            <textarea name="add_content"></textarea>
            <label>Aktywna:</label>
            <input type="checkbox" name="add_active" checked>
            <input type="submit" name="add_submit" value="Dodaj">
          </form>';
}

// Usuń podstronę
function UsunPodstrone($id)
{
    global $link;
    $query = "DELETE FROM page_list WHERE id='$id'";
    mysqli_query($link, $query);
}
// ----------LOGIKA------------

include("cfg.php");

session_start();

$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$db = 'moja_strona';
$link = new mysqli($dbhost, $dbuser, $dbpass, $db);

// Sprawdzanie czy formularz edycji został wysłany
if (isset($_POST['edit_submit'])) {
    $edit_id = isset($_POST['edit_id']) ? $_POST['edit_id'] : 0;
    $edit_title = $_POST['edit_title'];
    $edit_content = $_POST['edit_content'];
    $edit_active = isset($_POST['edit_active']) ? 1 : 0;

	// Zaktualizowanie danych podstrony w bazie danych
    $update_query = "UPDATE page_list SET page_title='$edit_title', page_content='$edit_content', status=$edit_active WHERE id=$edit_id";
    mysqli_query($link, $update_query);
	
	// Przekierowywanie na stronę administracyjną po zaktualizowaniu
    header('Location: admin.php');
    exit();
}


// Sprawdzanie czy formularz logowania został wysłany
if (isset($_POST['login_email']) && isset($_POST['login_pass'])) {
    $enteredLogin = $_POST['login_email'];
    $enteredPass = $_POST['login_pass'];

    require_once 'cfg.php';

	// Sprawdzanie poprawności danych logowania
    if ($enteredLogin === $login && $enteredPass === $pass) {
        $_SESSION['logged_in'] = true;
    } else {
        $error_message = "Błąd logowania. Spróbuj ponownie.";
    }
}

// Sprawdzanie czy formularz wylogowania został wysłany, jeśli tak - zakończenie sesji
if (isset($_POST['logout'])) {
    session_destroy();
    exit();
}

// Sprawdzanie czy użytkownik jest zalogowany
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
	// Wyświetlanie wylogowania
    echo '<form method="post" action="' . $_SERVER['REQUEST_URI'] . '">
            <input type="submit" name="logout" value="Wyloguj">
          </form>';
		  
	// Wyświetlanie linków do zarządzania kategoriami i produktami i contact
	echo '<a href="cat.php" target="_blank">Zarządzanie Kategoriami</a></br>';
	echo '<a href="products.php" target="_blank">Zarządzanie Produktami</a></br>';
    echo '<a href="contact.php" target="_blank">Kontakt</a>';
	// Wywoływanie funkcji do zarządzania podstronami
    ListaPodstron();
    EdytujPodstrone();
    DodajNowaPodstrone();
} else {
	
	// Wyświetl komunikatu o błędzie logowania
    echo isset($error_message) ? "<p style='color: red;'>$error_message</p>" : "";
	// Wyświetlenie formularza logowania
    echo FormularzLogowania();
}
echo '<a href="index.php" class="powrot">Powrót do strony</a>';
?>