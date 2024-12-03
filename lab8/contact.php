<?php
// contact.php

class Kontakt {

    public function PokazKontakt() {
        echo '<form action="contact.php" method="post">
                <label for="name">Imię:</label>
                <input type="text" id="name" name="name"><br>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email"><br>
                <label for="message">Wiadomość:</label>
                <textarea id="message" name="message"></textarea><br>
                <input type="submit" value="Wyślij">
              </form>';
    }

    public function WyslijMailKontakt() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $do = "admin@przyklad.com"; // Zmień na adres odbiorcy
            $temat = "Formularz kontaktowy";
            $imie = htmlspecialchars($_POST['name']);
            $email = htmlspecialchars($_POST['email']);
            $wiadomosc = htmlspecialchars($_POST['message']);
            $naglowki = "From: $email" . "\r\n" .
                        "Reply-To: $email" . "\r\n" .
                        "X-Mailer: PHP/" . phpversion();

            $trescMaila = "Imię: $imie\nEmail: $email\n\nWiadomość:\n$wiadomosc";

            if (mail($do, $temat, $trescMaila, $naglowki)) {
                echo "E-mail został wysłany pomyślnie!";
            } else {
                echo "Wysłanie e-maila nie powiodło się.";
            }
        }
    }

    public function PrzypomnijHaslo() {
        // To jest prosty i niezabezpieczony sposób, tylko do celów demonstracyjnych
        $do = "admin@przyklad.com"; // Zmień na adres e-mail administratora
        $temat = "Przypomnienie hasła";
        $wiadomosc = "Twoje hasło to: [twoje_haslo]";
        $naglowki = "From: no-reply@przyklad.com" . "\r\n" .
                    "Reply-To: no-reply@przyklad.com" . "\r\n" .
                    "X-Mailer: PHP/" . phpversion();

        if (mail($do, $temat, $wiadomosc, $naglowki)) {
            echo "Przypomnienie hasła zostało wysłane pomyślnie!";
        } else {
            echo "Wysłanie przypomnienia hasła nie powiodło się.";
        }
    }
}

// Przykład użycia
$kontakt = new Kontakt();
$kontakt->PokazKontakt();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kontakt->WyslijMailKontakt();
}

// Aby przypomnieć hasło (powinno być wywoływane w bezpieczny sposób)
$kontakt->PrzypomnijHaslo();

?>
