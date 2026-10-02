<?php
session_start();

require 'database.php';
require 'functions.php';

$category = null;

if (isset($_GET['category_id'])) {
    // US-10: items van één categorie tonen
    if (!is_numeric($_GET['category_id'])) {
        header('Location: items_index.php');
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM categories WHERE id = :id");
    $stmt->execute(['id' => $_GET['category_id']]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($category) {
    $sql = "SELECT items.*, categories.naam AS categorie_naam
            FROM items
            JOIN categories ON items.category_id = categories.id
            WHERE items.status = 'beschikbaar' AND items.deleted_at IS NULL
            AND items.category_id = :category_id
            ORDER BY items.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['category_id' => $category['id']]);
} else {
    $sql = "SELECT items.*, categories.naam AS categorie_naam
            FROM items
            JOIN categories ON items.category_id = categories.id
            WHERE items.status = 'beschikbaar' AND items.deleted_at IS NULL
            ORDER BY items.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
}
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

require 'header.php';
?>

<main class="container">
    <?php if ($category) : ?>
        <p class="breadcrumb"><a href="items_index.php">Aanbod</a> / <?php echo htmlspecialchars($category['naam']); ?></p>
        <h1><?php echo htmlspecialchars($category['naam']); ?></h1>
        <p class="lead"><?php echo htmlspecialchars($category['omschrijving']); ?></p>
    <?php else : ?>
        <h1>Aanbod</h1>
        <p class="lead">Alle beschikbare advertenties, nieuwste eerst.</p>
    <?php endif; ?>

    <?php if (empty($items)) : ?>
        <p class="empty">Er zijn hier nog geen advertenties.</p>
    <?php else : ?>
        <div class="grid">
            <?php foreach ($items as $item) : ?>
                <?php include 'item_card.php'; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<?php require 'footer.php'; ?>
