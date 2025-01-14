<?php


echo '<style>';
include('css/admin_style.css');
echo '</style>';

ob_start();
include("cfg.php");

session_start();

// Sprawdzanie, czy użytkownik jest zalogowany
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php'); // Przekierowanie do strony logowania
    exit();
}
// Sprawdzenie, czy istnieje koszyk w sesji, jeśli nie, tworzenie go
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Obsługa dodawania produktu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'DodajProdukt') {
        DodajProdukt();
    } elseif ($_POST['action'] === 'EdytujProdukt') {
        EdytujProdukt($_POST['id']);
    }
}

// Obsługa dodawania produktu do koszyka
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'DodajProdukt') {
        DodajProdukt();
    } elseif ($_POST['action'] === 'EdytujProdukt') {
        EdytujProdukt($_POST['id']);
    } elseif ($_POST['action'] === 'DodajDoKoszyka') {
        DodajDoKoszyka($_POST['id']);
    } elseif ($_POST['action'] === 'removeFromCart') {
        removeFromCart($_POST['id']);
    }
}

// Obsługa wyświetlania produktów
PokazProdukty();

// Funkcja dodająca produkt do bazy danych
function DodajProdukt()
{
    global $link;

    // Pobranie danych z formularza
    $title = $_POST['title'];
    $description = $_POST['description'];
    $expiration_date = $_POST['expiration_date'];
    $net_price = $_POST['net_price'];
    $vat_tax = $_POST['vat_tax'];
    $available_quantity = $_POST['available_quantity'];
    $availability_status = $_POST['availability_status'];
    $category = $_POST['category'];
    $size = $_POST['size'];

    // Sprawdzenie, czy plik został przesłany
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Obsługa przesłanego pliku
        $image_data = file_get_contents($_FILES['image']['tmp_name']);
        $image_data = base64_encode($image_data);
    } else {
        // Jeśli plik nie został przesłany - ignorowanie
        $image_data = '';
    }

    $creation_date = date('Y-m-d');
    $modification_date = $creation_date;

    // Wstawienie produktu do bazy danych
    $insert_query = "
    INSERT INTO products (
        title, description, creation_date, modification_date, expiration_date, net_price, vat_tax, available_quantity,
        availability_status, category, size, image
    )
    VALUES (
        '$title', '$description', '$creation_date', '$modification_date', '$expiration_date', '$net_price', '$vat_tax',
        '$available_quantity', '$availability_status', '$category', '$size', '$image_data'
    )
    ";

    $link->query($insert_query);

    // Przekierowywanie na stronę z listą produktów po dodaniu
    header('Location: products.php');
    exit();
}

// Funkcja wyświetlająca produkty
function PokazProdukty()
{
    global $link;

    $query = "SELECT * FROM products";
    $result = $link->query($query);
	
	

    echo '<h1>Lista Produktów</h1>';

    // Formularz dodawania produktu
    echo '<form method="post" action="" enctype="multipart/form-data">
            <input type="hidden" name="action" value="DodajProdukt">
            <label>Tytuł:</label>
            <input type="text" name="title" required>
            <label>Opis:</label>
            <textarea name="description" required></textarea>
            <label>Data Wygaśnięcia:</label>
            <input type="date" name="expiration_date" required>
            <label>Cena Netto:</label>
            <input type="text" name="net_price" required>
            <label>Podatek VAT:</label>
            <input type="text" name="vat_tax" required>
            <label>Ilość Dostępnych Sztuk:</label>
            <input type="text" name="available_quantity" required>
            <label>Status Dostępności:</label>
            <input type="text" name="availability_status" required>
            <label>Kategoria:</label>
            <input type="text" name="category" required>
            <label>Gabaryt Produktu:</label>
            <select name="size" required>
                <option value="small">Mały</option>
                <option value="medium">Średni</option>
                <option value="large">Duży</option>
            </select>
            <label>Zdjęcie:</label>
            <input type="file" name="image" accept="image/*" required>
            <input type="submit" name="DodajProdukt_submit" value="Dodaj Produkt">
          </form>';

    if ($result->num_rows > 0) {
        echo '<table border="1">
                <tr>
                    <th>ID</th>
                    <th>Tytuł</th>
                    <th>Opis</th>
                    <th>Data Utworzenia</th>
                    <th>Data Modyfikacji</th>
                    <th>Data Wygaśnięcia</th>
                    <th>Cena Netto</th>
                    <th>Podatek VAT</th>
                    <th>Ilość Dostępnych Sztuk</th>
                    <th>Status Dostępności</th>
                    <th>Kategoria</th>
                    <th>Gabaryt Produktu</th>
                    <th>Zdjęcie</th>
                    <th>Akcje</th>
                </tr>';
        while ($row = $result->fetch_assoc()) {
			
			echo $row['category'];
			$sql = 'SELECT * FROM categories WHERE id='.$row['category'].'';
			$result2 = $link->query($sql);
			$cat = "";
			
			while($row2 = $result2->fetch_assoc()){
				$cat = $row2['name'];
			}
			
			
            echo '<tr>';
            echo '<td>' . $row['id'] . '</td>';
            echo '<td>' . $row['title'] . '</td>';
            echo '<td>' . $row['description'] . '</td>';
            echo '<td>' . $row['creation_date'] . '</td>';
            echo '<td>' . $row['modification_date'] . '</td>';
            echo '<td>' . $row['expiration_date'] . '</td>';
            echo '<td>' . $row['net_price'] . '</td>';
            echo '<td>' . $row['vat_tax'] . '</td>';
            echo '<td>' . $row['available_quantity'] . '</td>';
            echo '<td>' . $row['availability_status'] . '</td>';
            echo '<td>' . $cat . '</td>';
            echo '<td>' . $row['size'] . '</td>';
            echo '<td><img src="data:image/jpeg;base64,' . $row['image'] . '" alt="' . $row['title'] . '"></td>';


			echo '<td>
                <form method="post" action="">
					<a href="?action=delete&id=' . $row['id'] . '">Usuń</a>
					<a href="?action=edit&id=' . $row['id'] . '">Edytuj</a></br>
                    <input type="hidden" name="action" value="DodajDoKoszyka">
                    <input type="hidden" name="id" value="' . $row['id'] . '">
                    <input type="hidden" name="title" value="' . $row['title'] . '">
                    <input type="hidden" name="net_price" value="' . $row['net_price'] . '">
                    <input type="hidden" name="vat_tax" value="' . $row['vat_tax'] . '">
                    <label>Ilość sztuk:</label>
					<input type="number" name="quantity" value="1" min="1">
					<input type="submit" value="Dodaj do koszyka">
                </form>
            </td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo 'Brak produktów.';
		
		
    }
	// Wyświetl koszyk
    PokazKoszyk();
}

// Obsługa Usuwania produktu
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $product_id = $_GET['id'];
    UsunProdukt($product_id);

    // Przekierowywanie na stronę z listą produktów po usunięciu
    header('Location: products.php');
    exit();
}

// Funkcja usuwająca produkt
function UsunProdukt($product_id)
{
    global $link;

    // Usunięcie produktu o zadanym ID
    $delete_query = "DELETE FROM products WHERE id = $product_id";
    $link->query($delete_query);
}

// Obsługa edycji produktu
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    EdytujProduktForm($_GET['id']);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'EdytujProdukt') {
        EdytujProdukt($_POST['id']);
    }
}

// Funkcja wyświetlająca formularz edycji produktu
function EdytujProduktForm($id)
{
    global $link;

    $query = "SELECT * FROM products WHERE id = $id LIMIT 1";
    $result = $link->query($query);
    $row = $result->fetch_assoc();

    // Wyświetl formularz edycji
    echo '<h2>Edytuj Produkt</h2>';
    echo '<form method="post" action="" enctype="multipart/form-data">
            <input type="hidden" name="action" value="EdytujProdukt">
            <input type="hidden" name="id" value="' . $row['id'] . '">
            <label>Tytuł:</label>
            <input type="text" name="title" value="' . $row['title'] . '" required>
            <label>Opis:</label>
            <textarea name="description" required>' . $row['description'] . '</textarea>
            <label>Data Wygaśnięcia:</label>
            <input type="date" name="expiration_date" value="' . $row['expiration_date'] . '" required>
            <label>Cena Netto:</label>
            <input type="text" name="net_price" value="' . $row['net_price'] . '" required>
            <label>Podatek VAT:</label>
            <input type="text" name="vat_tax" value="' . $row['vat_tax'] . '" required>
            <label>Ilość Dostępnych Sztuk:</label>
            <input type="text" name="available_quantity" value="' . $row['available_quantity'] . '" required>
            <label>Status Dostępności:</label>
            <input type="text" name="availability_status" value="' . $row['availability_status'] . '" required>
            <label>Kategoria:</label>
            <input type="text" name="category" value="' . $row['category'] . '" required>
            <label>Gabaryt Produktu:</label>
            <select name="size" value="' . $row['size'] . '" required>
                <option value="small">Mały</option>
                <option value="medium">Średni</option>
                <option value="large">Duży</option>
            </select>
            <label>Zdjęcie:</label>
            <input type="file" name="new_image" accept="image/*">
            <input type="submit" name="EdytujProdukt_submit" value="Zapisz zmiany">
          </form>';
}

// Funkcja obsługująca edycję produktu
function EdytujProdukt($id)
{
    global $link;

    // Pobranie danych z formularza
    $title = $_POST['title'];
    $description = $_POST['description'];
	$expiration_date = $_POST['expiration_date'];
    $net_price = $_POST['net_price'];
    $vat_tax = $_POST['vat_tax'];
    $available_quantity = $_POST['available_quantity'];
    $availability_status = $_POST['availability_status'];
    $category = $_POST['category'];
    $size = $_POST['size'];
	// Pobranie aktualnego zdjęcia
    $query = "SELECT image FROM products WHERE id = $id LIMIT 1";
    $result = $link->query($query);
    $row = $result->fetch_assoc();
    $current_image = $row['image'];

    // Sprawdzenie, czy użytkownik przesłał nowe zdjęcie
    if (isset($_FILES['new_image']) && $_FILES['new_image']['error'] === UPLOAD_ERR_OK) {
        // Obsługa przesłanego nowego pliku (zdjęcia)
        $new_image_data = file_get_contents($_FILES['new_image']['tmp_name']);
        $new_image_data = base64_encode($new_image_data);
    } else {
        // Jeśli użytkownik nie przesłał nowego zdjęcia, zachowaj aktualne zdjęcie
        $new_image_data = $current_image;
    }

	
	$modification_date = date('Y-m-d');
    $update_query = "
    UPDATE products
    SET
        title = '$title',
        description = '$description',
        expiration_date = '$expiration_date',
        net_price = '$net_price',
        vat_tax = '$vat_tax',
        available_quantity = '$available_quantity',
        availability_status = '$availability_status',
        category = '$category',
        size = '$size',
        modification_date = '$modification_date',
        image = '$new_image_data'
    WHERE id = $id
    ";

    $link->query($update_query);

    // Przekierowywanie na stronę z listą produktów po zaktualizowaniu
    header('Location: products.php');
    exit();
}

// Obsługa dodawania produktu do koszyka
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'DodajProdukt') {
        DodajProdukt();
    } elseif ($_POST['action'] === 'EdytujProdukt') {
        EdytujProdukt($_POST['id']);
    } elseif ($_POST['action'] === 'DodajDoKoszyka' && isset($_POST['product_id'])) {
        DodajDoKoszyka($_POST['product_id']);
    }
}
// Funkcja dodająca produkt do koszyka
function DodajDoKoszyka()
{
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'DodajDoKoszyka') {
        // Pobranie danych z formularza
        $id = $_POST['id'];
        $title = $_POST['title'];
        $net_price = $_POST['net_price'];
        $vat_tax = $_POST['vat_tax'];
        $quantity = $_POST['quantity'];

        // Sprawdzenie, czy koszyk istnieje w sesji
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }

        // Sprawdzenie, czy produkt jest już w koszyku
        $productIndex = IndexProduktuWKoszyku($id);

        // Dodanie lub zaktualizowanie produktu w koszyku
        if ($productIndex !== -1) {
            // Produkt już istnieje w koszyku - zaktualizuj ilość
            $_SESSION['cart'][$productIndex]['quantity'] += $quantity;
        } else {
            // Produkt nie istnieje w koszyku - dodaj nowy
            $_SESSION['cart'][] = array(
                'id' => $id,
                'title' => $title,
                'net_price' => $net_price,
                'vat_tax' => $vat_tax,
                'quantity' => $quantity,
            );
        }

        // Przekieruj z powrotem na stronę produktów po dodaniu do koszyka
        header('Location: products.php');
        exit();
    }
}
// Funkcja pomocnicza do znajdowania indeksu produktu w koszyku na podstawie ID
function IndexProduktuWKoszyku($productId)
{
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $index => $item) {
            if ($item['id'] == $productId) {
                return $index;
            }
        }
    }

    return -1;
}


// Funkcja usuwająca produkt z koszyka
function removeFromCart($productId)
{
    // Szukaj produktu w koszyku
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $productId) {
            // Usuń produkt z koszyka
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
}

// Funkcja wyświetlająca koszyk
function PokazKoszyk()
{
    echo '<h2>Koszyk</h2>';

    if (!empty($_SESSION['cart'])) {
        echo '<table border="1">
                <tr>
                    <th>ID</th>
                    <th>Tytuł</th>
                    <th>Cena brutto</th>
                    <th>Ilość</th>
                    <th>Wartość</th>
                    <th>Akcje</th>
                </tr>';
		 $totalValue = 0;
        foreach ($_SESSION['cart'] as $item) {
            echo '<tr>';
            echo '<td>' . $item['id'] . '</td>';
            echo '<td>' . $item['title'] . '</td>';
            echo '<td>' . number_format(CenaBrutto($item['net_price'], $item['vat_tax']), 2, '.', '') . '</td>';
            echo '<td>' . $item['quantity'] . '</td>';
            $itemValue = CenaBrutto($item['net_price'], $item['vat_tax']) * $item['quantity'];
            echo '<td>' . number_format($itemValue, 2, '.', '') . '</td>';
            echo '<td>';
            echo '<form method="post" action="">
                        <input type="hidden" name="action" value="removeFromCart">
                        <input type="hidden" name="id" value="' . $item['id'] . '">
                        <input type="submit" value="Usuń z koszyka">
                    </form>';
            echo '</td>';
            echo '</tr>';
            $totalValue += $itemValue;
        }

        echo '</table>';

        // Wyświetl łączną wartość koszyka
        echo '<p>Łączna wartość koszyka: ' . number_format($totalValue, 2, '.', '') . '</p>';
    } else {
        echo 'Koszyk jest pusty.';
    }
}


// Funkcja obliczająca cenę brutto na podstawie ceny netto i podatku VAT
function CenaBrutto($netPrice, $vatTax)
{
    return $netPrice * (1 + ($vatTax / 100));
}


echo '<a href="admin.php" class="powrot">Powrót do panelu administracyjnego</a>';

ob_end_flush();
$link->close();
?>
