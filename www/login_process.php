<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$wachtwoord = $_POST['wachtwoord'] ?? '';

if ($email == '' || $wachtwoord == '') {
    header('Location: login.php?error=1');
    exit;
}

require 'database.php';

$sql = "SELECT id, naam, wachtwoord, rol FROM users WHERE email = :email";
$stmt = $conn->prepare($sql);
$stmt->execute(['email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Generieke foutmelding: we verraden niet of het e-mailadres bestaat
if (!$user || !password_verify($wachtwoord, $user['wachtwoord'])) {
    header('Location: login.php?error=1');
    exit;
}

// Nieuw sessie-id na inloggen
session_regenerate_id(true);

$_SESSION['user_id'] = $user['id'];
$_SESSION['naam'] = $user['naam'];
$_SESSION['role'] = $user['rol'];

header('Location: index.php');
exit;
