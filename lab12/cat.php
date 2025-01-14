<?php
//----------STYL-------------

echo '<style>';
include('css/admin_style.css');
echo '</style>';

//---------FUNKCJE------------

// Funkcja dodająca nową kategorię
function DodajKategorie($parent_id, $name) {
    global $link;
    $parent_id = (int)$parent_id;
    $name = mysqli_real_escape_string($link, $name);
    $query = "INSERT INTO categories (parent_id, name) VALUES ('$parent_id', '$name')";
    mysqli_query($link, $query);
}

// Funkcja usuwająca kategorię
function UsunKategorie($category_id) {
    global $link;
    $category_id = (int)$category_id;
    $query = "DELETE FROM categories WHERE id = '$category_id'";
    mysqli_query($link, $query);
}

// Funkcja edytująca kategorię
function EdytujKategorie($category_id, $name, $parent_id) {
    global $link;
    $category_id = (int)$category_id;
    $name = mysqli_real_escape_string($link, $name);
    $parent_id = (int)$parent_id;

    $query = "UPDATE categories SET name = '$name', parent_id = '$parent_id' WHERE id = '$category_id'";
    mysqli_query($link, $query);
}

// Funkcja wyświetlająca kategorie
function PokazKategorie() {
    global $link;
    $query = "SELECT * FROM categories";
    $result = mysqli_query($link, $query);

    echo '<table border="1">
            <tr>
                <th>ID</th>
                <th>Nazwa Kategorii</th>
                <th>Matka</th>
                <th>Akcje</th>
            </tr>';

    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . $row['id'] . '</td>';
        echo '<td>' . $row['name'] . '</td>';
        echo '<td>' . $row['parent_id'] . '</td>';
        echo '<td>
                <form method="post" action="">
                    <input type="hidden" name="category_id" value="' . $row['id'] . '">
					<input type="hidden" name="action" value="edit">
					<label>Nazwa Kategorii:</label>
					<input type="text" name="name" value="' . $row['name'] . '" required>
					<label>Matka (ID):</label>
					<input type="number" name="parent_id" value="' . $row['parent_id'] . '" required>
					<input type="submit" name="edit_submit" value="Edytuj">
                </form>
                <form method="post" action="">
                    <input type="hidden" name="category_id" value="' . $row['id'] . '">
                    <input type="hidden" name="action" value="delete">
                    <input type="submit" name="delete_submit" value="Usuń">
                </form>
              </td>';
        echo '</tr>';
    }

    echo '</table>';
}

//------------LOGIKA------------

include("cfg.php");

session_start();

// Sprawdzanie, czy użytkownik jest zalogowany
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
	// Przekierowanie do strony logowania
    header('Location: login.php');
    exit();
}

// Obsługa formularzy
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $parent_id = $_POST['parent_id'];
            $name = $_POST['name'];
            DodajKategorie($parent_id, $name);
        } elseif ($_POST['action'] === 'edit') {
            $category_id = $_POST['category_id'];
            $name = $_POST['name'];
			$parent_id = $_POST['parent_id'];
            EdytujKategorie($category_id, $name, $parent_id);
        } elseif ($_POST['action'] === 'delete') {
            $category_id = $_POST['category_id'];
            UsunKategorie($category_id);
        }
    }
}

echo '<h1>Zarządzanie Kategoriami</h1>';

// Formularz dodawania kategorii
echo '<h2>Dodaj Kategorię</h2>';
echo '<form method="post" action="">
        <label>Nazwa Kategorii:</label>
        <input type="text" name="name" required>
        <label>Matka (ID):</label>
        <input type="number" name="parent_id" value="0" required>
        <input type="hidden" name="action" value="add">
        <input type="submit" name="add_submit" value="Dodaj">
      </form>';

// Wyświetlenie listy kategorii
echo '<h2>Lista Kategorii</h2>';
PokazKategorie();

// Dodanie przycisku/linku powrotu do admin.php
echo '<br><br>';
echo '<a href="admin.php" class="powrot">Powrót do panelu administracyjnego</a>';

?>
