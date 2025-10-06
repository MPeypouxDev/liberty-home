<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/user.php';

class AuthController {
    private $db;
    private $userModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->userModel = new User($this->db);
    }

    // Inscription
    public function register() {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validation
            if (empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['email']) || empty($_POST['password'])) {
                $errors[] = "Tous les champs obligatoires doivent être remplis";
            }

            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Email invalide";
            }

            if ($this->userModel->emailExists($_POST['email'])) {
                $errors[] = "Cet email est déjà utilisé";
            }

            if (strlen($_POST['password']) < 6) {
                $errors[] = "Le mot de passe doit contenir au moins 6 caractères";
            }

            // Si pas d'erreurs, on inscrit l'utilisateur
            if (empty($errors)) {
                $data = [
                    'nom' => htmlspecialchars($_POST['lastname']),
                    'prenom' => htmlspecialchars($_POST['firstname']),
                    'email' => htmlspecialchars($_POST['email']),
                    'mot_de_passe' => $_POST['password'],
                    'telephone' => htmlspecialchars($_POST['phone'] ?? null),
                    'date_naissance' => $_POST['birthdate'] ?? null,
                    'is_host' => true
                ];

                $userId = $this->userModel->register($data);

                if ($userId) {
                    $_SESSION['success'] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
                    header('Location:' . BASE_URL . 'views/auth/login.php');
                    exit();
                } else {
                    $errors[] = "Erreur lors de l'inscription";
                }
            }
        }

        return $errors;
    }

    // Connexion
    public function login() {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['email']) || empty($_POST['password'])) {
                $errors[] = "Email et mot de passe requis";
            } else {
                $user = $this->userModel->login($_POST['email'], $_POST['password']);

                if ($user) {
                    $_SESSION['user_id'] = $user['id_user'];
                    $_SESSION['user_name'] = $user['firstname'] . '' . $user['lastname'];
                    $_SESSION['is_host'] = $user['is_host'];

                    header('Location: ' . BASE_URL);
                    exit();
                } else {
                    $errors[] = "Email ou mot de passe incorrect";
                }
            }
        }

        return $errors;
    }

    // Déconnexion
    public function logout() {
        session_destroy();
        header('Location: ' . BASE_URL);
        exit();
    }
}