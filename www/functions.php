<?php

// De prijs staat in de database in centen: 1499 wordt "€ 14,99"
function formatPrijs($centen)
{
    return '€ ' . number_format($centen / 100, 2, ',', '.');
}

// Zet een ingevoerde prijs in euro's ("14,99" of "14.99") om naar centen (1499)
function prijsNaarCenten($invoer)
{
    $invoer = str_replace(',', '.', trim($invoer));
    return (int) round((float) $invoer * 100);
}

// Controleert of een ingevoerde prijs een geldig bedrag in euro's is (max. 2 decimalen)
function isGeldigePrijs($invoer)
{
    return preg_match('/^\d{1,6}([.,]\d{1,2})?$/', trim($invoer)) === 1;
}

// Toont een lijst met foutmeldingen en een terug-link, en stopt daarna het script
function toonFouten($errors, $terugUrl)
{
    require 'header.php';
    echo '<main class="container">';
    echo '<div class="alert alert-error"><p><strong>Er ging iets mis:</strong></p><ul>';
    foreach ($errors as $error) {
        echo '<li>' . htmlspecialchars($error) . '</li>';
    }
    echo '</ul></div>';
    echo '<a class="btn" href="' . htmlspecialchars($terugUrl) . '">&larr; Terug naar het formulier</a>';
    echo '</main>';
    require 'footer.php';
    exit;
}

// Valideert de velden van het advertentieformulier (create en update) en geeft een lijst met fouten terug
function valideerItem($conn, $titel, $omschrijving, $prijs, $conditie, $categoryId, $fotoUrl)
{
    $errors = [];

    if ($titel == '') {
        $errors[] = 'Vul een titel in.';
    } elseif (mb_strlen($titel) > 100) {
        $errors[] = 'De titel mag maximaal 100 tekens lang zijn.';
    }

    if ($omschrijving == '') {
        $errors[] = 'Vul een omschrijving in.';
    }

    if (!isGeldigePrijs($prijs)) {
        $errors[] = 'Vul een geldige prijs in, bijvoorbeeld 12,50.';
    } elseif (prijsNaarCenten($prijs) <= 0) {
        $errors[] = 'De prijs moet hoger zijn dan € 0,00.';
    }

    if (!in_array($conditie, ['nieuw', 'goed', 'redelijk', 'slecht'])) {
        $errors[] = 'Kies een geldige conditie.';
    }

    if (!is_numeric($categoryId)) {
        $errors[] = 'Kies een categorie.';
    } else {
        // Bestaat de gekozen categorie echt?
        $stmt = $conn->prepare("SELECT id FROM categories WHERE id = :id");
        $stmt->execute(['id' => $categoryId]);
        if (!$stmt->fetch()) {
            $errors[] = 'Kies een geldige categorie.';
        }
    }

    if ($fotoUrl == '' || !filter_var($fotoUrl, FILTER_VALIDATE_URL)) {
        $errors[] = 'De foto-url is geen geldige url.';
    }

    return $errors;
}
