<?php
class User {
    private $conn;
    private $table = 'users';

    public_function __construct($db) {
        $this->conn = $db;
    }

    // Inscription d'un nouvel utilisateur
    public function register($data) {
        $query = "INSERT INTO" . $this->table . "
        (firstname, lastname, email, password, phone, birthdate, is_host)
        VALUES (:firstname, :lastname, :email, :password, :phone, :birthdate, :is_host)";

        $stmt = $this->conn->prepare($query);

        // Hash du mot de passe
        $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);

        $stmt->bindParam(':lastname', $data['lastname']);
        $stmt->bindParam(':firstname', $data['firstname']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':phone', $data['phone']);
        $stmt->bindParam(':birthdate', $data['birthdate']);
        $stmt->bindParam(':is_host', $data['is_host']);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Connexion
    public function login($email, $password) {
        $query = "SELECT * FROM" . $this->table . " WHERE email = :email AND active = 1 LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch();
            if (password_verify($password, $user['password'])) {
                return $user;
            }
        }
        return false;
    }

    // Vérifier si un e-mail existe déjà
    public function emailExists($email) {
        $query = "SELECT id_user FROM " . $this->table . "WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Récupérer un utilisateur par ID
    public function getUserById($id) {
        $query = "SELECT id_user FROM " . $this->table . " WHERE id_user = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }
}