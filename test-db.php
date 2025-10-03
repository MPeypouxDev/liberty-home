<?php
require_once 'config/database.php';

$database = new Database();
$conn = $database->getConnection();

if ($conn) {
    echo "Connexion réussie !";

    // Test des données
    $stmt = $conn->query("SELECT * FROM users");
    $users = $stmt->fetchAll();

    echo "Nombres d'utilisateurs : " . count($users) . "<br>";
    foreach($users as $user) {
        echo "- {$user['prenom']} {$user['nom']} ({$user['email']})";
    }
} else {
    echo "Erreur de connexion";
}
?>