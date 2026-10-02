<?php
session_start();

require 'database.php';
require 'functions.php';

$id = $_GET['id'];

// JOIN met categories, users en seller_profiles voor alle gegevens op één pagina
$sql = "SELECT items.*,
               categories.naam AS categorie_naam,
               users.naam AS verkoper_naam,
               seller_profiles.bio,
               seller_profiles.telefoonnummer,
               seller_profiles.beoordelingsscore
        FROM items
        JOIN categories ON items.category_id = categories.id
        JOIN users ON items.user_id = users.id
        LEFT JOIN seller_profiles ON seller_profiles.user_id = users.id
        WHERE items.id = $id AND items.deleted_at IS NULL";
$stmt = $conn->query($sql);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

require 'header.php';

if (!$item) {
    echo '<main class="container"><h1>Advertentie niet gevonden</h1>';
    echo '<p>Deze advertentie bestaat niet (meer).</p>';
    echo '<a class="btn" href="items_index.php">&larr; Terug naar het aanbod</a></main>';
    require 'footer.php';
    exit;
}

$isEigenaar = isset($_SESSION['user_id']) && $_SESSION['user_id'] == $item['user_id'];
?>

<main class="container">
    <p class="breadcrumb">
        <a href="items_index.php">Aanbod</a> /
        <a href="items_index.php?category_id=<?php echo $item['category_id']; ?>"><?php echo htmlspecialchars($item['categorie_naam']); ?></a> /
        <?php echo htmlspecialchars($item['titel']); ?>
    </p>

    <?php if (isset($_GET['created'])) : ?>
        <div class="alert alert-success">Je advertentie staat online!</div>
    <?php elseif (isset($_GET['updated'])) : ?>
        <div class="alert alert-success">Je advertentie is bijgewerkt.</div>
    <?php endif; ?>

    <div class="detail">
        <div class="detail-image">
            <?php if (!empty($item['foto_url'])) : ?>
                <img src="<?php echo htmlspecialchars($item['foto_url']); ?>" alt="<?php echo htmlspecialchars($item['titel']); ?>">
            <?php else : ?>
                <div class="no-image">Geen foto</div>
            <?php endif; ?>
        </div>

        <div class="detail-info">
            <h1><?php echo htmlspecialchars($item['titel']); ?></h1>
            <p class="price price-large"><?php echo formatPrijs($item['prijs']); ?></p>

            <div class="badges">
                <span class="badge badge-<?php echo htmlspecialchars($item['conditie']); ?>">Conditie: <?php echo htmlspecialchars($item['conditie']); ?></span>
                <?php if ($item['status'] == 'verkocht') : ?>
                    <span class="badge badge-sold">Verkocht</span>
                <?php else : ?>
                    <span class="badge badge-available">Beschikbaar</span>
                <?php endif; ?>
            </div>

            <h2>Omschrijving</h2>
            <p class="description"><?php echo nl2br(htmlspecialchars($item['omschrijving'])); ?></p>

            <p class="muted">Geplaatst op <?php echo date('d-m-Y', strtotime($item['created_at'])); ?></p>

            <?php if ($isEigenaar) : ?>
                <a href="item_update.php?id=<?php echo $item['id']; ?>" class="btn btn-primary">Advertentie wijzigen</a>
            <?php endif; ?>

            <aside class="seller">
                <h2>Verkoper</h2>
                <p class="seller-name"><?php echo htmlspecialchars($item['verkoper_naam']); ?></p>
                <?php if ($item['beoordelingsscore'] !== null) : ?>
                    <p class="rating">&#9733; <?php echo htmlspecialchars($item['beoordelingsscore']); ?> / 5</p>
                <?php endif; ?>
                <?php if (!empty($item['bio'])) : ?>
                    <p><?php echo htmlspecialchars($item['bio']); ?></p>
                <?php endif; ?>
                <?php if (isset($_SESSION['user_id'])) : ?>
                    <p>Tel: <?php echo htmlspecialchars($item['telefoonnummer']); ?></p>
                <?php else : ?>
                    <p class="muted"><a href="login.php">Log in</a> om het telefoonnummer te zien.</p>
                <?php endif; ?>
            </aside>
        </div>
    </div>
</main>

<?php require 'footer.php'; ?>
