<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?required=1');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: item_create.php');
    exit;
}

require 'database.php';
require 'functions.php';

$titel = trim($_POST['titel'] ?? '');
$omschrijving = trim($_POST['omschrijving'] ?? '');
$prijs = $_POST['prijs'] ?? '';
$conditie = $_POST['conditie'] ?? '';
$categoryId = $_POST['category_id'] ?? '';
$fotoUrl = trim($_POST['foto_url'] ?? '');

$errors = valideerItem($conn, $titel, $omschrijving, $prijs, $conditie, $categoryId, $fotoUrl);

if (!empty($errors)) {
    toonFouten($errors, 'item_create.php');
}

$sql = "INSERT INTO items (titel, omschrijving, prijs, conditie, foto_url, user_id, category_id)
        VALUES (:titel, :omschrijving, :prijs, :conditie, :foto_url, :user_id, :category_id)";
$stmt = $conn->prepare($sql);
$stmt->execute([
    'titel' => $titel,
    'omschrijving' => $omschrijving,
    'prijs' => prijsNaarCenten($prijs),
    'conditie' => $conditie,
    'foto_url' => $fotoUrl == '' ? null : $fotoUrl,
    'user_id' => $_SESSION['user_id'],
    'category_id' => $categoryId
]);

$itemId = $conn->lastInsertId();

header("Location: item_detail.php?id=$itemId&created=1");
exit;
