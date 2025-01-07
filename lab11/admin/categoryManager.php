<?php
// ========================
// Funkcja: Dodaj Kategorię
// ========================
function addCategory($conn, $name, $parentId = 0) {
    $stmt = $conn->prepare("INSERT INTO categories (nazwa, matka) VALUES (?, ?)");
    $stmt->bind_param("si", $name, $parentId);
    $stmt->execute();
    $stmt->close();
}

// ========================
// Funkcja: Usuń Kategorię
// ========================
function deleteCategory($conn, $id) {
    // Usuwamy również podkategorie
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ? OR matka = ?");
    $stmt->bind_param("ii", $id, $id);
    $stmt->execute();
    $stmt->close();
}

// ========================
// Funkcja: Edytuj Kategorię
// ========================
function editCategory($conn, $id, $name, $parentId = 0) {
    $stmt = $conn->prepare("UPDATE categories SET nazwa = ?, matka = ? WHERE id = ?");
    $stmt->bind_param("sii", $name, $parentId, $id);
    $stmt->execute();
    $stmt->close();
}

// ========================
// Funkcja: Pobierz Kategorię
// ========================
function getCategory($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $category = $result->fetch_assoc();
    $stmt->close();
    return $category;
}

// ========================
// Funkcja: Wyświetl Kategorie
// ========================
function displayCategories($conn, $parentId = 0, $level = 0) {
    $stmt = $conn->prepare("SELECT id, nazwa FROM categories WHERE matka = ?");
    $stmt->bind_param("i", $parentId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo str_repeat("--", $level) . " " . htmlspecialchars($row['nazwa']) . "<br>";
        displayCategories($conn, $row['id'], $level + 1);
    }

    $stmt->close();
}

// ========================
// Funkcja: Wyświetl Kategorie w Select
// ========================
function displayCategoriesForSelect($conn, $selectedId = 0, $parentId = 0, $level = 0) {
    $stmt = $conn->prepare("SELECT id, nazwa FROM categories WHERE matka = ?");
    $stmt->bind_param("i", $parentId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $selected = ($row['id'] == $selectedId) ? 'selected' : '';
        echo '<option value="' . (int)$row['id'] . '" ' . $selected . '>' . str_repeat("--", $level) . ' ' . htmlspecialchars($row['nazwa']) . '</option>';
        displayCategoriesForSelect($conn, $selectedId, $row['id'], $level + 1);
    }

    $stmt->close();
}
?>


<?php
class ZarzadzanieProduktami {
    private $pdo;

    public function __construct($host, $dbname, $user, $password) {
        $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    }

    public function DodajProdukt($tytul, $opis, $data_wygasniecia, $cena_netto, $podatek_vat, $ilosc_sztuk, $status_dostepnosci, $kategoria, $gabaryt, $zdjecie) {
        $stmt = $this->pdo->prepare("INSERT INTO produkty (tytul, opis, data_wygasniecia, cena_netto, podatek_vat, ilosc_sztuk, status_dostepnosci, kategoria, gabaryt, zdjecie) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$tytul, $opis, $data_wygasniecia, $cena_netto, $podatek_vat, $ilosc_sztuk, $status_dostepnosci, $kategoria, $gabaryt, $zdjecie]);
    }

    public function UsunProdukt($id) {
        $stmt = $this->pdo->prepare("DELETE FROM produkty WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function EdytujProdukt($id, $tytul, $opis, $data_wygasniecia, $cena_netto, $podatek_vat, $ilosc_sztuk, $status_dostepnosci, $kategoria, $gabaryt, $zdjecie) {
        $stmt = $this->pdo->prepare("UPDATE produkty SET tytul = ?, opis = ?, data_wygasniecia = ?, cena_netto = ?, podatek_vat = ?, ilosc_sztuk = ?, status_dostepnosci = ?, kategoria = ?, gabaryt = ?, zdjecie = ? WHERE id = ?");
        $stmt->execute([$tytul, $opis, $data_wygasniecia, $cena_netto, $podatek_vat, $ilosc_sztuk, $status_dostepnosci, $kategoria, $gabaryt, $zdjecie, $id]);
    }

    public function PokazProdukty() {
        $stmt = $this->pdo->query("SELECT * FROM produkty");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
