<?php
session_start();
require('cfg.php');
include('categorymanager.php');
function FormularzLogowania($error = '') {
    $wynik = '
<div class="logowanie">
<h1 class="heading">Panel CMS:</h1>
<div class="logowanie">
      '.($error ? '<p class="error">'.$error.'</p>' : '').'
<form method="post" name="LoginForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URI'].'">
<table class="logowanie">
<tr><td class="log4_t">[email]</td><td><input type="text" name="login_email" class="logowanie" /></td></tr>
<tr><td class="log4_t">[haslo]</td><td><input type="password" name="login_pass" class="logowanie" /></td></tr>
<tr><td>&nbsp</td><td><input type="submit" name="x1_submit" class="logowanie" value="zaloguj" /></td></tr>
</table>
</form>
</div>
</div>
    ';
 
    return $wynik;
}


function checkLogin() {
    require('cfg.php');
    if (isset($_POST['username']) && isset($_POST['password'])) {
        if ($_POST['username'] == $GLOBALS['login'] && $_POST['password'] == $GLOBALS['pass']) {
            $_SESSION['loggedin'] = true;
            return true;
        } else {
            echo 'Błędny login lub hasło.<br>';
            FormularzLogowania();
            return false;
        }
    }
    return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
}

if (!checkLogin()) {
    exit;
}

// Lista podstron
function ListaPodstron() {
    global $conn;
    $query = "SELECT id, page_title FROM page_list";
    $result = mysqli_query($conn, $query);

    echo '<table>';
    echo '<tr><th>ID</th><th>Tytuł</th><th>Akcje</th></tr>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . $row['id'] . '</td>';
        echo '<td>' . $row['page_title'] . '</td>';
        echo '<td>
                <a href="edit.php?id=' . $row['id'] . '">Edytuj</a> 
                <a href="delete.php?id=' . $row['id'] . '">Usuń</a>
                <a href="?action=list_categories">Lista kategorii</a> 
                <a href="?action=add_category">Dodaj nową kategorię</a>
              </td>';
        echo '</tr>';
    }
    echo '</table>';
}

// Edycja podstrony
function EdytujPodstrone() {
    global $conn;

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $query = "SELECT * FROM page_list WHERE id = $id LIMIT 1";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);

        echo '<form method="post" action="">
                <input type="text" name="title" value="' . $row['page_title'] . '" required>
                <textarea name="content" required>' . $row['page_content'] . '</textarea>
                <input type="checkbox" name="status" ' . ($row['status'] ? 'checked' : '') . '> Aktywna
                <button type="submit">Zapisz</button>
              </form>';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $content = $_POST['content'];
            $status = isset($_POST['status']) ? 1 : 0;

            $updateQuery = "UPDATE page_list SET page_title='$title', page_content='$content', status=$status WHERE id=$id LIMIT 1";
            mysqli_query($conn, $updateQuery);
            echo 'Podstrona zaktualizowana.';
        }
    }
}

// Dodawanie nowej podstrony
function DodajNowaPodstrone() {
    echo '<form method="post" action="">
            <input type="text" name="title" placeholder="Tytuł" required>
            <textarea name="content" placeholder="Treść" required></textarea>
            <input type="checkbox" name="status"> Aktywna
            <button type="submit">Dodaj</button>
          </form>';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        global $conn;
        $title = $_POST['title'];
        $content = $_POST['content'];
        $status = isset($_POST['status']) ? 1 : 0;

        $insertQuery = "INSERT INTO page_list (page_title, page_content, status) VALUES ('$title', '$content', $status)";
        mysqli_query($conn, $insertQuery);
        echo 'Nowa podstrona dodana.';
    }
}

// Usuwanie podstrony
function UsunPodstrone() {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        global $conn;
        $deleteQuery = "DELETE FROM page_list WHERE id = $id LIMIT 1";
        mysqli_query($conn, $deleteQuery);
        echo 'Podstrona usunięta.';
    }
}

// Wywołanie odpowiednich funkcji w zależności od akcji
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'list':
            ListaPodstron();
            break;
        case 'edit':
            EdytujPodstrone();
            break;
        case 'add':
            DodajNowaPodstrone();
            break;
        case 'delete':
            UsunPodstrone();
            break;
            
            case 'list_categories':
                ListaKategorii($conn); // Wywołanie funkcji listującej kategorie
                break;
            case 'add_category':
                DodajNowaKategorie($conn); // Dodanie nowej kategorii
                break;
            case 'edit_category':
                if (isset($_GET['id'])) {
                    EdytujKategorie((int)$_GET['id'], $conn); // Edycja kategorii
                }
                break;
            case 'delete_category':
                if (isset($_GET['id'])) {
                    UsunKategorie((int)$_GET['id'], $conn); // Usunięcie kategorii
                }
                break;        
            default:
            echo 'Nieznana akcja.';
    }
} else {
    ListaPodstron();
}
// ========================
// Funkcja: Lista Kategorii
// ========================
function ListaKategorii($conn) {
    echo '<h3>Lista Kategorii</h3>';
    echo '<a href="?action=categoryAdd">Dodaj kategorię</a><br><br>';
    echo '<table border="1">';
    echo '<tr><th>ID</th><th>Nazwa</th><th>Akcje</th></tr>';
    $sql = "SELECT * FROM categories ORDER BY matka, nazwa LIMIT 100";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['id']) . '</td>';
        echo '<td>' . htmlspecialchars($row['nazwa']) . '</td>';
        echo '<td>';
        echo '<a href="?action=categoryEdit&id=' . (int)$row['id'] . '">Edytuj</a> | ';
        echo '<a href="?action=categoryDelete&id=' . (int)$row['id'] . '" onclick="return confirm(\'Czy na pewno chcesz usunąć tę kategorię?\')">Usuń</a>';
        echo '</td>';
        echo '</tr>';
    }
    echo '</table>';
}

// ========================
// Funkcja: Dodaj Kategorię
// ========================
function DodajKategorie($conn) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $conn->real_escape_string($_POST['name']);
        $parentId = isset($_POST['parent_id']) ? (int)$_POST['parent_id'] : 0;
        addCategory($conn, $name, $parentId);
        echo "Kategoria dodana pomyślnie.";
        echo '<br><a href="?action=categoryList">Powrót do listy kategorii</a>';
    } else {
        echo '<h3>Dodaj Kategorię</h3>';
        echo '<form method="POST" action="">';
        echo 'Nazwa: <input type="text" name="name" required><br>';
        echo 'Kategoria nadrzędna: ';
        echo '<select name="parent_id">';
        echo '<option value="0">Brak</option>';
        displayCategoriesForSelect($conn);
        echo '</select><br>';
        echo '<input type="submit" value="Dodaj">';
        echo '</form>';
    }
}

// ========================
// Funkcja: Edytuj Kategorię
// ========================
function EdytujKategorie($conn, $id) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $conn->real_escape_string($_POST['name']);
        $parentId = isset($_POST['parent_id']) ? (int)$_POST['parent_id'] : 0;
        editCategory($conn, $id, $name, $parentId);
        echo "Kategoria zaktualizowana pomyślnie.";
        echo '<br><a href="?action=categoryList">Powrót do listy kategorii</a>';
    } else {
        $category = getCategory($conn, $id);
        if (!$category) {
            echo "Kategoria nie istnieje.";
            return;
        }
        echo '<h3>Edytuj Kategorię</h3>';
        echo '<form method="POST" action="">';
        echo 'Nazwa: <input type="text" name="name" value="' . htmlspecialchars($category['nazwa']) . '" required><br>';
        echo 'Kategoria nadrzędna: ';
        echo '<select name="parent_id">';
        echo '<option value="0">Brak</option>';
        displayCategoriesForSelect($conn, $category['matka']);
        echo '</select><br>';
        echo '<input type="submit" value="Zapisz">';
        echo '</form>';
    }
}

// ========================
// Funkcja: Usuń Kategorię
// ========================
function UsunKategorie($conn, $id) {
    deleteCategory($conn, $id);
    echo "Kategoria usunięta pomyślnie.";
    echo '<br><a href="?action=categoryList">Powrót do listy kategorii</a>';
}
?>