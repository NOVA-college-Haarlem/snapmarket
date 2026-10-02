<?php
session_start();

require 'functions.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: register.php');
    exit;
}

$naam = trim($_POST['naam'] ?? '');
$email = trim($_POST['email'] ?? '');
$wachtwoord = $_POST['wachtwoord'] ?? '';
$wachtwoordHerhaal = $_POST['wachtwoord_herhaal'] ?? '';
$telefoonnummer = trim($_POST['telefoonnummer'] ?? '');
$bio = trim($_POST['bio'] ?? '');

// Validatie
$errors = [];

if ($naam == '') {
    $errors[] = 'Vul je naam in.';
} elseif (mb_strlen($naam) > 100) {
    $errors[] = 'Je naam mag maximaal 100 tekens lang zijn.';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Vul een geldig e-mailadres in.';
}

if (mb_strlen($wachtwoord) < 8) {
    $errors[] = 'Je wachtwoord moet minimaal 8 tekens lang zijn.';
} elseif ($wachtwoord !== $wachtwoordHerhaal) {
    $errors[] = 'De wachtwoorden komen niet overeen.';
}

if (!preg_match('/^[0-9+\- ]{10,15}$/', $telefoonnummer)) {
    $errors[] = 'Vul een geldig telefoonnummer in (10 tot 15 cijfers).';
}

if (mb_strlen($bio) > 500) {
    $errors[] = 'Je bio mag maximaal 500 tekens lang zijn.';
}

if (!empty($errors)) {
    toonFouten($errors, 'register.php');
}

require 'database.php';

// Wachtwoord nooit als platte tekst opslaan
$hash = password_hash($wachtwoord, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (naam, email, wachtwoord, rol) VALUES (:naam, :email, :wachtwoord, 'user')";
$stmt = $conn->prepare($sql);

try {
    $stmt->execute([
        'naam' => $naam,
        'email' => $email,
        'wachtwoord' => $wachtwoord
    ]);
} catch (PDOException $e) {
    // 23000 = UNIQUE constraint: dit e-mailadres bestaat al
    if ($e->getCode() == 2300) {
        toonFouten(['Dit e-mailadres is al in gebruik.'], 'register.php');
    }
    toonFouten(['Er is iets misgegaan bij het opslaan. Probeer het opnieuw.'], 'register.php');
}

// Bij elke nieuwe user hoort precies één verkopersprofiel (1-op-1 relatie)
$userId = $conn->lastInsertId();

$sql = "INSERT INTO seller_profiles (user_id, bio, telefoonnummer) VALUES (:user_id, :bio, :telefoonnummer)";
$stmt = $conn->prepare($sql);
$stmt->execute([
    'user_id' => $userId,
    'bio' => $bio,
    'telefoonnummer' => $telefoonnummer
]);

header('Location: login.php?registered=1');
exit;
