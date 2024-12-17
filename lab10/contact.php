<?php
// contact.php - Obsługa formularza kontaktowego

class Kontakt {

    // Wyświetlenie formularza kontaktowego
    public function PokazKontakt() {
        echo '<form action="contact.php" method="post">
                <label for="name">Imię:</label>
                <input type="text" id="name" name="name" required><br>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required><br>
                <label for="message">Wiadomość:</label>
                <textarea id="message" name="message" required></textarea><br>
                <input type="submit" value="Wyślij">
              </form>';
    }

    // Wysyłanie wiadomości e-mail z formularza kontaktowego
    public function WyslijMailKontakt() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Zabezpieczenie danych wejściowych
            $imie      = htmlspecialchars(trim($_POST['name']));
            $email     = htmlspecialchars(trim($_POST['email']));
            $wiadomosc = htmlspecialchars(trim($_POST['message']));

            // Dane do wysyłki e-maila
            $do         = "admin@przyklad.com"; // Zmień na adres odbiorcy
            $temat      = "Formularz kontaktowy";
            $naglowki   = "From: $email\r\nReply-To: $email\r\nX-Mailer: PHP/" . phpversion();
            $trescMaila = "Imię: $imie\nEmail: $email\n\nWiadomość:\n$wiadomosc";

            // Wysłanie e-maila
            if (mail($do, $temat, $trescMaila, $naglowki)) {
                echo "E-mail został wysłany pomyślnie!";
            } else {
                echo "Wysłanie e-maila nie powiodło się.";
            }
        }
    }

    // Przypomnienie hasła (przykład - wymaga zabezpieczeń)
    public function PrzypomnijHaslo() {
        // To jest prosty i niezabezpieczony sposób, tylko do celów demonstracyjnych
        $do       = "admin@przyklad.com"; // Zmień na adres e-mail administratora
        $temat    = "Przypomnienie hasła";
        $wiadomosc = "Twoje hasło to: [twoje_haslo]";
        $naglowki = "From: no-reply@przyklad.com\r\nReply-To: no-reply@przyklad.com\r\nX-Mailer: PHP/" . phpversion();

        if (mail($do, $temat, $wiadomosc, $naglowki)) {
            echo "Przypomnienie hasła zostało wysłane pomyślnie!";
        } else {
            echo "Wysłanie przypomnienia hasła nie powiodło się.";
        }
    }
}

// Przykład użycia klasy Kontakt
$kontakt = new Kontakt();
$kontakt->PokazKontakt();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kontakt->WyslijMailKontakt();
}

// Przypomnienie hasła (należy wywoływać w bezpieczny sposób)
$kontakt->PrzypomnijHaslo();
?>