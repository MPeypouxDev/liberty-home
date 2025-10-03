<?php
require_once '../../config/config.php';
require_once '../../controllers/AuthController.php';

$controller = new AuthController();
$errors = $controller->register();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Liberty Home</title>
    <link rel="stylesheet" href="<?= ASSETS_URL ?>css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-header">
            <h1>Terminer l'inscription</h1>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="error-messages">
                <?php foreach($errors as $error): ?>
                    <p class="error"><?= $error ?></p>
                    <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="" class="auth-form">
                <div class="form-group">
                    <input type="text" name="firstname" placeholder="Prénom" required>
                </div>

                <div class="form-group">
                    <input type="text" name="lastname" placeholder="Nom" required>
                </div>

                <div class="form-group">
                    <input type="date" name="birthdate" placeholder="Date de naissance">
                </div>

                <div class="form-group">
                    <input type="tel" name="phone" placeholder="Téléphone">
                </div>

                <div class="form-group">
                    <input type="email" name="email" placeholder="Adresse e-mail" required>
                </div>

                <div class="form-group">
                    <input type="password" name="password" placeholder="Mot de passe" required>
                    <button type="button" class="toggle-password">Afficher</button>
                </div>

                <button type="submit" class="btn-primary">Accepter et Continuer</button>
            </form>

            <p class="auth-footer">
                Déjà inscrit ? <a href="login.php">Se connecter</a>
            </p>
    </div>
    
</body>
</html>