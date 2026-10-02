<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?required=1');
    exit;
}

require 'database.php';

$stmt = $conn->prepare("SELECT * FROM categories ORDER BY naam");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

require 'header.php';
?>

<main class="container container-small">
    <h1>Advertentie plaatsen</h1>

    <form action="item_create_process.php" method="post" class="form">
        <div class="form-group">
            <label for="titel">Titel</label>
            <input type="text" id="titel" name="titel" maxlength="100" required>
        </div>
        <div class="form-group">
            <label for="omschrijving">Omschrijving</label>
            <textarea id="omschrijving" name="omschrijving" rows="5" required></textarea>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="prijs">Prijs in euro's</label>
                <input type="text" id="prijs" name="prijs" inputmode="decimal" placeholder="12,50" required>
            </div>
            <div class="form-group">
                <label for="conditie">Conditie</label>
                <select id="conditie" name="conditie" required>
                    <option value="">Kies een conditie</option>
                    <option value="nieuw">Nieuw</option>
                    <option value="goed">Goed</option>
                    <option value="redelijk">Redelijk</option>
                    <option value="slecht">Slecht</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="category_id">Categorie</label>
            <select id="category_id" name="category_id" required>
                <option value="">Kies een categorie</option>
                <?php foreach ($categories as $category) : ?>
                    <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['naam']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="foto_url">Foto-url (optioneel)</label>
            <input type="url" id="foto_url" name="foto_url" maxlength="255" placeholder="https://...">
        </div>
        <button type="submit" class="btn btn-primary">Plaatsen</button>
    </form>
</main>

<?php require 'footer.php'; ?>
