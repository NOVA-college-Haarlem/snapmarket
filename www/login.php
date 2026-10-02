<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

require 'header.php';
?>

<main class="container container-small">
    <h1>Inloggen</h1>

    <?php if (isset($_GET['registered'])) : ?>
        <div class="alert alert-success">Je account is aangemaakt. Je kunt nu inloggen.</div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])) : ?>
        <div class="alert alert-error">Ongeldige inloggegevens.</div>
    <?php endif; ?>

    <?php if (isset($_GET['required'])) : ?>
        <div class="alert alert-info">Log in om verder te gaan.</div>
    <?php endif; ?>

    <form action="login_process.php" method="post" class="form">
        <div class="form-group">
            <label for="email">E-mailadres</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="wachtwoord">Wachtwoord</label>
            <input type="password" id="wachtwoord" name="wachtwoord" required>
        </div>
        <button type="submit" class="btn btn-primary">Inloggen</button>
    </form>

    <p>Nog geen account? <a href="register.php">Registreer je hier</a>.</p>
</main>

<?php require 'footer.php'; ?>
