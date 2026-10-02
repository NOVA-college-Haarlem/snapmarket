<?php
session_start();

// Ingelogde gebruikers hoeven zich niet nog een keer te registreren
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

require 'header.php';
?>

<main class="container container-small">
    <h1>Registreren als verkoper</h1>
    <p class="lead">Maak een account aan en plaats direct je eerste advertentie.</p>

    <form action="register_process.php" method="post" class="form">
        <fieldset>
            <legend>Account</legend>
            <div class="form-group">
                <label for="naam">Naam</label>
                <input type="text" id="naam" name="naam" maxlength="100" required>
            </div>
            <div class="form-group">
                <label for="email">E-mailadres</label>
                <input type="email" id="email" name="email" maxlength="150" required>
            </div>
            <div class="form-group">
                <label for="wachtwoord">Wachtwoord (minimaal 8 tekens)</label>
                <input type="password" id="wachtwoord" name="wachtwoord" minlength="8" required>
            </div>
            <div class="form-group">
                <label for="wachtwoord_herhaal">Herhaal wachtwoord</label>
                <input type="password" id="wachtwoord_herhaal" name="wachtwoord_herhaal" minlength="8" required>
            </div>
        </fieldset>

        <fieldset>
            <legend>Verkopersprofiel</legend>
            <div class="form-group">
                <label for="telefoonnummer">Telefoonnummer</label>
                <input type="tel" id="telefoonnummer" name="telefoonnummer" placeholder="0612345678" required>
            </div>
            <div class="form-group">
                <label for="bio">Korte bio</label>
                <textarea id="bio" name="bio" rows="3" maxlength="500" placeholder="Vertel kopers iets over jezelf"></textarea>
            </div>
        </fieldset>

        <button type="submit" class="btn btn-primary">Account aanmaken</button>
    </form>

    <p>Heb je al een account? <a href="login.php">Log hier in</a>.</p>
</main>

<?php require 'footer.php'; ?>
