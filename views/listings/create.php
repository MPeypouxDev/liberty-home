<?php
require_once '../../config/config.php';
require_once '../../controllers/ListingController.php';

$controller = new ListingController();
$errors = $controller->create();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une annonce - Liberty Home</title>
    <link rel="stylesheet" href="<?= ASSETS_URL ?>css/style.css">
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1>Créer une annonce</h1>
            <a href="listing.php" class="btn-secondary">Retour à mes annonces</a>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="error-messages">
                <?php foreach($errors as $error): ?>
                    <p class="error"><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="" class="listing-form">
                <div class="form-group">
                    <label for="title">Titre de l'annonce *</label>
                    <input type="text id="title" name="title" placeholder="Ex: Studio Cosy avec Vue sur Lac" required>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" rows="5" placeholder="Décrivez votre logement..."></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="property_type">Type de logement *</label>
                        <select name="property_type" id="property_type" required>
                            <option value="apartment">Appartement</option>
                            <option value="house">Maison</option>
                            <option value="studio">Studio</option>
                            <option value="Villa">Villa</option>
                            <option value="tree-house">Cabane</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="capacity_max">Capacité (personnes) *</label>
                        <input type="number" id="capacity_max" name="capacity_max" min="1" required>
                    </div>
                </div>

                <div class="form-section">
                    <h2>Localisation</h2>

                    <div class="form-group">
                        <label for="address">Adresse</label>
                        <input type="text" id="address" name="address" placeholder="123 Rue de la paix">
                    </div>

                    <div class=form-group>
                        <label for="city">Ville *</label>
                        <input type="text" id="city" name="city" placeholder="Paris" required>
                    </div>
                </div>

                <div class="form-section">
                    <h2>Prix</h2>

                    <div class="form-group">
                        <label for="price_per_night">Prix par nuit (€) *</label>
                        <input type="number" id="price_per_night" name="price_per_night" step="0.01" min="0" placeholder="89.00" required>
                    </div>
                </div>

                <div class="form-section">
                    <h2>Equipements</h2>

                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="parking" value="1">
                            <span>Parking</span>
                        </label>

                        <label class="checkbox-label">
                            <input type="checkbox" name="instant_booking" value="1">
                            <span>Réservation instantanée</span>
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn-primary">Créer l'annonce</button>
            </form>
    </div>
    
</body>
</html>