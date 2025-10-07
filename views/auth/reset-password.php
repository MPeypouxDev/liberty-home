<?php
require_once '../../config/config.php';
require_once '../../controllers/AuthController.php';

$controller = new AuthController();
$message = '';
$messageType = '';
$token = $_GET['token'] ?? '';

// Vérification du token
if (empty($token)) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $controller->resetPassword($_POST['token'], $_POST['password'], $_POST['password_confirm']);
    $message = $result['message'];
    $messageType = $result['type'];

    // Si succèes, redirection vers le login
    if ($messageType === 'success') {
        header("refresh:2;url=login.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau mot de passe - Liberty Home</title>
    <link rel="stylesheet" href="<?= ASSETS_URL ?>css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-header">
            <h1>Nouveau mot de passe</h1>
            <p>Choisissez un nouveau mot de passe pour votre compte</p>
        </div>

        <?php if ($message): ?>

        <?php if ($messageType !== 'success'): ?>
            <form method="POST" action="" class="auth-form">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

                <div class="form-group">
                    <input type="password" name="password" placeholder="Nouveau mot de passe" required minlength="6">
                </div>

                <div class="form-group">
                    <input type="password" name="password_confirm" placeholder="Confirmer le nouveau mot de passe" required minlength="6">
                </div>

                <button type="submit" class="btn-primary">Réinitialiser le mot de passe</button>
            </form>
            <?php endif; ?>
            
            <p class="auth-footer">
                <a href="login.php">Retour à la connexion</a>
            </p>
    </div>
    
</body>
</html>