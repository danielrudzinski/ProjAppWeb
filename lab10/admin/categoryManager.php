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