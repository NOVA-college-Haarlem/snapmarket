<?php
session_start();

require 'database.php';
require 'functions.php';

// De 6 nieuwste beschikbare advertenties
$sql = "SELECT items.*, categories.naam AS categorie_naam
        FROM items
        JOIN categories ON items.category_id = categories.id
        WHERE items.status = 'beschikbaar' AND items.deleted_at IS NULL
        ORDER BY items.created_at ASC
        LIMIT 6";
$stmt = $conn->prepare($sql);
$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT * FROM categories ORDER BY naam");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

require 'header.php';
?>

<section class="hero">
    <div class="container">
        <h1>Tweedehands kopen en verkopen, gewoon in de buurt.</h1>
        <p>Geen hoge commissies, wel persoonlijk contact. Bekijk het aanbod of zet zelf iets te koop.</p>
        <div class="hero-actions">
            <a href="items_index.php" class="btn btn-white">Bekijk het aanbod</a>
            <a href="item_create.php" class="btn btn-light">Verkoop iets</a>
        </div>
    </div>
</section>

<main class="container">
    <section>
        <h2>Categorieën</h2>
        <ul class="category-chips">
            <?php foreach ($categories as $category) : ?>
                <li>
                    <a href="items_index.php?category_id=<?php echo $category['id']; ?>">
                        <?php echo htmlspecialchars($category['naam']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>

    <section>
        <div class="section-header">
            <h2>Net binnen</h2>
            <a href="items_index.php">Alles bekijken &rarr;</a>
        </div>
        <div class="grid">
            <?php foreach ($items as $item) : ?>
                <?php include 'item_card.php'; ?>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<?php require 'footer.php'; ?>
