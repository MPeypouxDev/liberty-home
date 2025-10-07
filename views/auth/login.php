<?php
require_once '../../config/config.php';
require_once '../../controllers/AuthController.php';

$controller = new AuthController();
$errors = $controller->login();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Liberty Home</title>
    <link rel="stylesheet" href="<?= ASSETS_URL ?>css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-header">
            <h1>Bienvenue sur Liberty Home</h1>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="success-message">
                <p><?= $_SESSION['success'] ?></p>
            </div>
            <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="error-messages">
                    <?php foreach($errors as $error): ?>
                        <p class="error"><?= $error ?></p>
                        <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <form method="POST" action="" class="auth-form">
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Adresse e-mail" required>
                    </div>

                    <div class="form-group">
                        <input type="password" name="password" placeholder="Mot de passe" required>
                    </div>

                    <div class="forgot-password-link">
                        <a href="forgot-password.php">Mot de passe oublié ?</a>
                    </div>

                    <button type="submit" class="btn-primary">Continuer</button>
                </form>

                <div class="divider">ou</div>

                <a href="register.php" class="btn-secondary">Continuer avec un E-mail</a>

                <p class="auth-footer">
                    Pas encore de compte ? <a href="register.php">S'inscrire</a>
                </p>
    </div>
    
</body>
</html>