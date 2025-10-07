<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/listing.php';

class ListingController {
    private $db;
    private $listingModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->listingModel = new Listing($this->db);
    }

    // Créer une annonce
    public function create() {
        $errors = [];

        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'views/auth/login.php');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validation obligatoire
            if (empty($_POST['title']) || empty($_POST['city']) || empty($_POST['price_per_night']) || empty($_POST['capacity_max'])) {
                $errors[] = "Tous les champs obligatoires doivent être remplis";
            }

            if (!is_numeric($_POST['price_per_night']) || $_POST['price_per_night'] <= 0) {
                $errors[] = "Le pri doit être un nombre positif";
            }

            if (!is_numeric($_POST['capacity_max']) || $_POST['capacity_max'] <= 0) {
                $errors[] = "La capacité doit être un nombre positif";
            }

            // Si pas d'erreurs, créer l'annonce
            if (empty($errors)) {
                $data = [
                    'user_id' => $_SESSION['user_id'],
                    'title' => htmlspecialchars($_POST['title']),
                    'description' => htmlspecialchars($_POST['description'] ?? ''),
                    'address' => htmlspecialchars($_POST['address'] ?? ''),
                    'city' => htmlspecialchars($_POST['city']),
                    'price_per_night' => floatval($_POST['price_per_night']),
                    'capacity_max' => intval($_POST['capacity_max']),
                    'property_type' => htmlspecialchars($_POST['property_type'] ?? 'apartment'),
                    'wifi' => isset($_POST['wifi']) ? 1 : 0,
                    'parking' => isset($_POST['parking']) ? 1 : 0,
                    'instant_booking' => isset($_POST['instant_booking']) ? 1 : 0
                ];

                $listingId = $this->listingModel->create($data);

                if ($listingId) {
                    $_SESSION['success'] = "Annonce créée avec succès !";
                    header('Location: ' . BASE_URL . 'views/listings/my-listings.php');
                    exit();
                } else {
                    $errors[] = "Erreur lors de la création de l'annonce";
                }
            }
        }

        return $errors;
    }

    // Récupérer toutes les annonces
    public function getAll($limit = 20, $offset = 0) {
        return $this->listingModel->getAll($limit, $offset);
    }

    // Récupérer une annonce par ID
    public function getById($id) {
        return $this->listingModel->getById($id);
    }

    // Récupérer les annonces de l'utilisateur connecté
    public function getMyListings() {
        if (!isset($_SESSION['user_id'])) {
            return [];
        }
        return $this->listingModel->getUserById($_SESSION['user_id']);
    }

    // Modifier une annonce
    public function update($id) {
        $errors = [];

        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'views/auth/login.php');
            exit();
        }

        // Vérification que l'annonce appartient à l'utilisateur
        $listing = $this->listingModel->getById($id);
        if (!$listing || $listing['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = "Vous n'avez pas accès à cette annonce";
            header('Location: ' . BASE_URL . 'views/listings/my-listings.php');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validation
            if (empty($_POST['title']) || empty($_POST['city']) || empty($_POST['price_per_night']) || empty($_POST['capacity_max'])) {
                $errors[] = "Tous les champs obligatoires doivent être remplis";
            }

            if (!is_numeric($_POST['price_per_night']) || $_POST['price_per_night'] <= 0) {
                $errors[] = "Le prix doit être positif";
            }

            if (!is_numeric($_POST['capacity_max']) || $_POST['capacity_max'] <= 0) {
                $errors[] = "La capacité d'accueil doit être un nombre positif";
            }

            // Mettre à jour, si aucune erreur
            if (empty($errors)) {
                $data = [
                    'user_id' => $_SESSION['user_id'],
                    'title' => htmlspecialchars($_POST['title']),
                    'description' => htmlspecialchars($_POST['description'] ?? ''),
                    'address' => htmlspecialchars($_POST['address'] ?? ''),
                    'city' => htmlspecialchars($_POST['city']),
                    'price_per_night' => floatval($_POST['price_per_night']),
                    'capacity_max' => intval($_POST['capacity_max']),
                    'property_type' => htmlspecialchars($_POST['property_type'] ?? 'apartment'),
                    'wifi' => isset($_POST['wifi']) ? 1 : 0,
                    'parking' => isset($_POST['parking']) ? 1 : 0,
                    'instant_booking' => isset($_POST['instant_booking']) ? 1 : 0
                ];

                if ($this->listingModel->update($id, $data)) {
                    $_SESSION['success'] = "Annonce modifiée avec succès !";
                    header('Location: ' . BASE_URL . 'views/listings/my-listings.php');
                    exit();
                } else {
                    $errors[] = "Erreur lors de la modification";
                }
            }
        }

        return ['errors' => $errors, 'listing' => $listing];
    }

    // Supprimer une annonce
    public function delete($id) {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }

        return $this->listingModel->delete($id), $_SESSION['user_id'];
    }

    // Activer ou désactiver une annonce
    public function toggleAvailability($id) {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }

        return $this->listingModel->toggleAvailability($id, $_SESSION['user_id']);
    }
}