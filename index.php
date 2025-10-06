<?php

// Démarrer la session (pour gérer les utilisateurs connectés)
session_start();

// Charger la configuration (à créer plus tard)
// require_once 'config/database.php';

// Afficher les erreurs en développement (à retirer en production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Liberty Home - Trouvez votre logement idéal">
    
    <title>Liberty Home</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/images/favicon.png">
    
    <link rel="stylesheet" href="assets/sass/style.sass">
    <script src="assets/js/carousel.js"></script>
    <script src="assets/js/interactions.js"></script>
    
    <!-- Fonts Google (optionnel) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

    <!-- ========================================
         HEADER / NAVIGATION
         ======================================== -->
    <header class="app-header">
        <div class="logo">
            <h1>Liberty Home</h1>
        </div>
        
        <nav class="main-nav">
            <!-- Boutons de navigation (cachés pour l'instant sur mobile) -->
            <button class="btn-filter">🔍 Filtres</button>
            <button class="btn-profile">👤 Profil</button>
        </nav>
    </header>

    <!-- ========================================
         CAROUSEL PRINCIPAL
         ======================================== -->
    <main class="carousel-container" id="mainCarousel">
        
        <?php
        // ========================================
        // DONNÉES DE TEST (temporaire)
        // Plus tard, on récupérera ça depuis MySQL
        // ========================================
        
        $properties = [
            [
                'id' => 1,
                'title' => 'Studio Moderne - Paris',
                'location' => 'Quartier Latin',
                'surface' => '25m²',
                'price' => 850,
                'image' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800',
                'landlord' => 'Marie Dubois'
            ],
            [
                'id' => 2,
                'title' => 'Loft Industriel - Lyon',
                'location' => 'Part-Dieu',
                'surface' => '60m²',
                'price' => 1200,
                'image' => 'https://images.unsplash.com/photo-1502672260066-6bc36a0ce291?w=800',
                'landlord' => 'Jean Martin'
            ],
            [
                'id' => 3,
                'title' => 'T2 Lumineux - Bordeaux',
                'location' => 'Centre-ville',
                'surface' => '45m²',
                'price' => 950,
                'image' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800',
                'landlord' => 'Sophie Laurent'
            ],
            [
                'id' => 4,
                'title' => 'Colocation Cosy - Toulouse',
                'location' => 'Capitole',
                'surface' => '80m²',
                'price' => 600,
                'image' => 'https://images.unsplash.com/photo-1502672023488-70e25813eb80?w=800',
                'landlord' => 'Alex Petit'
            ]
        ];
        
        // ========================================
        // BOUCLE : Générer une carte par annonce
        // ========================================
        
        foreach ($properties as $property) :
        ?>
        
        <!-- CARTE D'UNE ANNONCE -->
        <article class="property-card" 
                 data-property-id="<?php echo $property['id']; ?>"
                 data-price="<?php echo $property['price']; ?>">
            
            <!-- IMAGE DE FOND -->
            <div class="property-image">
                <img src="<?php echo htmlspecialchars($property['image']); ?>" 
                     alt="<?php echo htmlspecialchars($property['title']); ?>"
                     loading="lazy">
            </div>
            
            <!-- OVERLAY AVEC INFORMATIONS -->
            <div class="property-overlay">
                
                <!-- Informations principales -->
                <div class="property-info">
                    <h2><?php echo htmlspecialchars($property['title']); ?></h2>
                    
                    <p class="location">
                        📍 <?php echo htmlspecialchars($property['location']); ?> 
                        • <?php echo htmlspecialchars($property['surface']); ?>
                    </p>
                    
                    <p class="price"><?php echo number_format($property['price'], 0, ',', ' '); ?>€/mois</p>
                    
                    <p class="landlord">
                        Proposé par <strong><?php echo htmlspecialchars($property['landlord']); ?></strong>
                    </p>
                </div>
                
                <!-- Boutons d'action -->
                <div class="property-actions">
                    <button class="btn-action btn-like" 
                            data-action="like" 
                            data-property-id="<?php echo $property['id']; ?>">
                        ❤️ J'aime
                    </button>
                    
                    <button class="btn-action btn-comment" 
                            data-action="comment"
                            data-property-id="<?php echo $property['id']; ?>">
                        💬 Commentaires
                    </button>
                    
                    <button class="btn-action btn-contact" 
                            data-action="contact"
                            data-property-id="<?php echo $property['id']; ?>">
                        📧 Contacter
                    </button>
                </div>
                
            </div>
            
        </article>
        
        <?php endforeach; ?>
        
    </main>

    <!-- ========================================
         INDICATEURS
         ======================================== -->
    <div class="carousel-counter">1 / <?php echo count($properties); ?></div>
    
    <!-- Barre de progression (optionnelle) -->
    <div class="carousel-progress-bar">
        <div class="carousel-progress"></div>
    </div>

    <!-- ========================================
         SCRIPTS JAVASCRIPT
         ======================================== -->
    
    <!-- Notre carousel -->
    <script src="assets/js/carousel.js"></script>
    
    <!-- Scripts des interactions (likes, commentaires, etc.) -->
    <script src="assets/js/interactions.js"></script>
    
    <script>
        // Initialisation après chargement du DOM
        document.addEventListener('DOMContentLoaded', function() {
            console.log('✅ Page chargée avec <?php echo count($properties); ?> annonces');
            
            // Données PHP accessibles en JavaScript
            window.locastayData = {
                totalProperties: <?php echo count($properties); ?>,
                userId: <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'null'; ?>
            };
        });
    </script>

</body>
</html>