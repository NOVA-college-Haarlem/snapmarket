<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?required=1');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: my_items.php');
    exit;
}

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    header('Location: my_items.php');
    exit;
}

require 'database.php';
require 'functions.php';

$id = $_POST['id'];

$titel = trim($_POST['titel'] ?? '');
$omschrijving = trim($_POST['omschrijving'] ?? '');
$prijs = $_POST['prijs'] ?? '';
$conditie = $_POST['conditie'] ?? '';
$categoryId = $_POST['category_id'] ?? '';
$fotoUrl = trim($_POST['foto_url'] ?? '');
$status = $_POST['status'] ?? '';

$errors = valideerItem($conn, $titel, $omschrijving, $prijs, $conditie, $categoryId, $fotoUrl);

if (!in_array($status, ['beschikbaar', 'verkocht'])) {
    $errors[] = 'Kies een geldige status.';
}

if (!empty($errors)) {
    toonFouten($errors, "item_update.php?id=$id");
}

$sql = "UPDATE items SET
            titel = :titel,
            omschrijving = :omschrijving,
            prijs = :prijs,
            conditie = :conditie,
            status = :status,
            foto_url = :foto_url,
            category_id = :category_id
        WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([
    'titel' => $titel,
    'omschrijving' => $omschrijving,
    'prijs' => prijsNaarCenten($prijs),
    'conditie' => $conditie,
    'status' => $status,
    'foto_url' => $fotoUrl == '' ? null : $fotoUrl,
    'category_id' => $categoryId,
    'id' => $id
]);

header("Location: item_detail.php?id=$id&updated=1");
exit;
