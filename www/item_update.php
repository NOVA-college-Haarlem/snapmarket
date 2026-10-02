<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?required=1');
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: my_items.php');
    exit;
}

require 'database.php';

$stmt = $conn->prepare("SELECT * FROM items WHERE id = :id AND deleted_at IS NULL");
$stmt->execute(['id' => $_GET['id']]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

// Alleen de eigenaar mag zijn eigen advertentie wijzigen
if (!$item || $item['user_id'] != $_SESSION['user_id']) {
    require 'header.php';
    echo '<main class="container"><h1>Geen toegang</h1>';
    echo '<p>Deze advertentie bestaat niet of is niet van jou.</p>';
    echo '<a class="btn" href="my_items.php">&larr; Naar mijn advertenties</a></main>';
    require 'footer.php';
    exit;
}

$stmt = $conn->prepare("SELECT * FROM categories ORDER BY naam");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

require 'header.php';
?>

<main class="container container-small">
    <h1>Advertentie wijzigen</h1>

    <form action="item_update_process.php" method="post" class="form">
        <input type="hidden" name="id" value="<?php echo $item['id']; ?>">

        <div class="form-group">
            <label for="titel">Titel</label>
            <input type="text" id="titel" name="titel" maxlength="100" required value="<?php echo htmlspecialchars($item['titel']); ?>">
        </div>
        <div class="form-group">
            <label for="omschrijving">Omschrijving</label>
            <textarea id="omschrijving" name="omschrijving" rows="5" required><?php echo htmlspecialchars($item['omschrijving']); ?></textarea>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="prijs">Prijs in euro's</label>
                <input type="text" id="prijs" name="prijs" inputmode="decimal" required value="<?php echo $item['prijs']; ?>">
            </div>
            <div class="form-group">
                <label for="conditie">Conditie</label>
                <select id="conditie" name="conditie" required>
                    <?php foreach (['nieuw', 'goed', 'redelijk', 'slecht'] as $conditie) : ?>
                        <option value="<?php echo $conditie; ?>" <?php if ($item['conditie'] == $conditie) echo 'selected'; ?>>
                            <?php echo ucfirst($conditie); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="category_id">Categorie</label>
                <select id="category_id" name="category_id" required>
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?php echo $category['id']; ?>" <?php if ($item['id'] == $category['id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($category['naam']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="beschikbaar" <?php if ($item['status'] == 'beschikbaar') echo 'selected'; ?>>Beschikbaar</option>
                    <option value="verkocht" <?php if ($item['status'] == 'verkocht') echo 'selected'; ?>>Verkocht</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="foto_url">Foto-url (optioneel)</label>
            <input type="url" id="foto_url" name="foto_url" maxlength="255" value="<?php echo htmlspecialchars($item['foto_url'] ?? ''); ?>">
        </div>
        <button type="submit" class="btn btn-primary">Opslaan</button>
        <a href="item_detail.php?id=<?php echo $item['id']; ?>" class="btn">Annuleren</a>
    </form>
</main>

<?php require 'footer.php'; ?>
