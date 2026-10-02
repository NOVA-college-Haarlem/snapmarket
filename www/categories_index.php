<?php
session_start();

require 'database.php';

// LEFT JOIN zodat ook categorieën zonder items getoond worden (aantal = 0)
$sql = "SELECT categories.id, categories.naam, categories.omschrijving, COUNT(items.id) AS aantal
        FROM categories
        LEFT JOIN items ON items.category_id = categories.id
            AND items.status = 'beschikbaar'
            AND items.deleted_at IS NULL
        GROUP BY categories.id, categories.naam, categories.omschrijving
        ORDER BY categories.naam";
$stmt = $conn->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

require 'header.php';
?>

<main class="container">
    <h1>Categorieën</h1>

    <div class="category-grid">
        <?php foreach ($categories as $category) : ?>
            <a class="category-card" href="items_index.php?category_id=<?php echo $category['id']; ?>">
                <h2><?php echo htmlspecialchars($category['naam']); ?></h2>
                <p><?php echo htmlspecialchars($category['omschrijving']); ?></p>
                <span class="count"><?php echo $category['aantal']; ?> beschikbaar</span>
            </a>
        <?php endforeach; ?>
    </div>
</main>

<?php require 'footer.php'; ?>
