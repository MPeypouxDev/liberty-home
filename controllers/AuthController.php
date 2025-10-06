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
                    'lastname' => htmlspecialchars($_POST['lastname']),
                    'firstname' => htmlspecialchars($_POST['firstname']),
                    'email' => htmlspecialchars($_POST['email']),
                    'password' => $_POST['password'],
                    'phone' => htmlspecialchars($_POST['phone'] ?? null),
                    'birthdate' => $_POST['birthdate'] ?? null,
                    'adress' => null,
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
                    $_SESSION['user_id'] = $user['id'];
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

    // Mot de passe oublié
    public function forgotPassword($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['type' => 'error', 'message' => 'Email invalide'];
        }

        if (!$this->userModel->emailExists($email)) {
            return ['type' => 'success', 'message' => 'Si cet email existe, vous recevrez un lien de réinitialisation du mot de passe'];
        }

        // Générer le token
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strotime('+1 hour'));

        // Sauvegarde du token
        if ($this->userModel->saveResetToken($email, $token, $expiry)) {
            // Créer le lien de réinitialisation
            $resetLink = BASE_URL . "views/auth/reset-password.php?token=" . $token;

            return [
                'type' => 'success',
                'message' => 'Un lien de réinitialisation a été généré. Pour le moment cliquez ici : ' . $resetLink
            ];
        }

        return ['type' => 'error', 'message' => 'Erreur lors de la génération du lien'];
    }
}