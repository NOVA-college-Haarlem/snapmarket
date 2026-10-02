<?php

$isIngelogd = isset($_SESSION['user_id']);

?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SnapMarket - Tweedehands in de buurt</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <header class="site-header">
        <div class="container header-inner">
            <a href="index.php" class="logo">Snap<span>Market</span></a>
            <nav>
                <ul>
                    <li><a href="items_index.php">Aanbod</a></li>
                    <li><a href="categories_index.php">Categorieën</a></li>
                    <?php if ($isIngelogd) : ?>
                        <li><a href="my_items.php">Mijn advertenties</a></li>
                        <li><a href="item_create.php" class="btn btn-primary">+ Plaats advertentie</a></li>
                        <li class="nav-user">Hoi, <?php echo htmlspecialchars($_SESSION['naam']); ?></li>
                        <li><a href="logout.php">Uitloggen</a></li>
                    <?php else : ?>
                        <li><a href="login.php">Inloggen</a></li>
                        <li><a href="register.php" class="btn btn-primary">Registreren</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
