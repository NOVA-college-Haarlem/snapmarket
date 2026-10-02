<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?required=1');
    exit;
}

require 'database.php';
require 'functions.php';

$sql = "SELECT items.*, categories.naam AS categorie_naam
        FROM items
        JOIN categories ON items.category_id = categories.id
        WHERE items.user_id = :user_id AND items.deleted_at IS NULL
        ORDER BY items.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

require 'header.php';
?>

<main class="container">
    <div class="section-header">
        <h1>Mijn advertenties</h1>
        <a href="item_create.php" class="btn btn-primary">+ Nieuwe advertentie</a>
    </div>

    <?php if (empty($items)) : ?>
        <p class="empty">Je hebt nog geen advertenties geplaatst.</p>
    <?php else : ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Titel</th>
                    <th>Categorie</th>
                    <th>Prijs</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item) : ?>
                    <tr>
                        <td><a href="item_detail.php?id=<?php echo $item['id']; ?>"><?php echo htmlspecialchars($item['titel']); ?></a></td>
                        <td><?php echo htmlspecialchars($item['categorie_naam']); ?></td>
                        <td><?php echo formatPrijs($item['prijs']); ?></td>
                        <td><?php echo htmlspecialchars($item['status']); ?></td>
                        <td><a href="item_update.php?id=<?php echo $item['id']; ?>">Wijzigen</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

<?php require 'footer.php'; ?>
