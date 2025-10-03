<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../modals/user.php';

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
        }
    }
}