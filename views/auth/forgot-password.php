<?php
require_once '../../config/config.php';
require_once '../../controllers/AuthController.php';

$controller = new AuthController();
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $controller->forgotPassword($_POST['email']);
    $message = $result['message'];
    $messageType = $result['type'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - Liberty Home</title>
    <link rel="stylesheet" href="<?= ASSETS_URL ?>css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-header">
            <h1>Réinitialiser le mot de passe</h1>
            <p>Entrez votre adresse email pour recevoir un lien de réinitialisation</p>
        </div>
        <?php if ($message): ?>
            <div class="<?= $messageType === 'success' ? 'success-message' : 'error-messages' ?>">
                <p class="<?= $messageType === 'error' ? 'error' : '' ?>"><?= htmlspecialchars($message) ?></p>
            </div>
            <?php endif; ?>

            <form method="POST" action="" class="auth-form">
                <div class="form-group">
                    <input type="email" name="email" placeholder="Adresse e-mail" required>
                </div>

                <button type="submit" class="btn-primary">Envoyer le lien</button>
            </form>

            <p class="auth-footer">
                <a href="login.php">Retour à la connexion</a>
            </p>
    </div>
</body>
</html>
