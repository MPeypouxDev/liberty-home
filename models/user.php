<?php
class User {
    private $conn;
    private $table = 'users';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Inscription d'un nouvel utilisateur
    public function register($data) {
        $query = "INSERT INTO " . $this->table . "
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
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
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
        $query = "SELECT id FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Récupérer un utilisateur par ID
    public function getUserById($id) {
        $query = "SELECT id FROM " . $this->table . " WHERE id_user = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Sauvegarder le token
    public function saveResetToken($email, $token, $expiry) {
        // Suppression des éventuels token précédents
        $deleteQuery = "DELETE FROM password_resets WHERE email = :email";
        $deleteStmt = $this->conn->prepare($deleteQuery);
        $deleteStmt->bindParam(':email', $email);
        $deleteStmt->execute();

        // Insertion du nouveau token
        $query = "INSERT INTO password_resets (email, token, expiry) VALUES (:email, :token, :expiry)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':expiry', $expiry);
        return $stmt->execute();
    }

    // Vérification du token
    public function verifyResetToken($token) {
        $query = "SELECT email FROM password_resets WHERE token = :token AND expiry > NOW() LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch();
        }
        return false;
    }

    // Réinitialiser le mot de passe
    public function resetPassword($token, $newPassword) {
        $tokenData = $this->verifyResetToken($token);

        if (!$tokenData) {
            return false;
        }

        // Hasher le nouveau mot de passe
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Mettre à jour le mot de passe
        $query = "UPDATE " . $this->table . " SET password = :password WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':email', $tokenData['email']);

        if ($stmt->execute()) {
            // Suppression du token utilisé
            $deleteQuery = "DELETE FROM password_resets WHERE token = :token";
            $deleteStmt = $this->conn->prepare($deleteQuery);
            $deleteStmt->bindParam(':token', $token);
            $deleteStmt->execute();

            return true;
        }

        return false;
    }
}