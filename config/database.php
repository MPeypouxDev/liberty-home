<?php
// Configuration de la connexion à la database

define('DB_HOST', 'localhost');
define('DB_NAME', 'liberty-home');
define('DB_USER', 'root');
define('DB_PASS', '');

class Database {
    private $conn = null;

    public function getConnection() {
        try {
            $this->conn = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            return $this->conn;
        } catch(PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }
}