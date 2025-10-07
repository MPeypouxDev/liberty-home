<?php
class Listing {
    private $conn;
    private $table = 'listings';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Créer une nouvelle annonce
    public function create($data) {
        $query = "INSERT INTO " . $this->table . "
                  (user_id, title, description, address, city, price_per_night, capacity_max, property_type, wifi, parking, instant_booking)
                  VALUES (:user_id, :title, :description, :address, :city, :price_per_night,
                          :capacity_max, :property_type, :wifi, :parking, :instant_booking)";
        
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':address', $data['address']);
        $stmt->bindParam(':city', $data['city']);
        $stmt->bindParam(':price_per_night', $data['price_per_night']);
        $stmt->bindParam(':capacity_max', $data['capacity_max']);
        $stmt->bindParam(':property_type', $data['property_type']);
        $stmt->bindParam(':wifi', $data['wifi']);
        $stmt->bindParam(':parking', $data['parking']);
        $stmt->bindParam(':instant_booking', $data['instant_booking']);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Récupérer toutes les annonces disponibles
    public function getAll($limit = 20, $offset = 0) {
        $query = "SELECT l.*, u.firstname, u.lastname, u.profile_picture
                  FROM " . $this->table . " l
                  LEFT JOIN users u ON l.user_id = u.id
                  WHERE l.is_available = 1
                  ORDER BY l.created_at DESC
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Récupérer une annonce par son ID
    public function getById($id) {
        $query = "SELECT l.*, u.firstname, u.lastname, u.profile_picture
                  FROM " . $this->table . " l
                  LEFT JOIN users u ON l.user_id = u.id
                  WHERE l.id = :id
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    // Récupérer les annonces d'un utilisateur
    public function getByUserId($userId) {
        $query = "SELECT * FROM " . $this->table . "
        WHERE user_id = :user_id
        ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Mettre à jour une annonce
    public function update($id, $data) {
        $query = "UPDATE " . $this->table . "
                 SET title = :title,
                     description = :description,
                     address = :address,
                     city = :city,
                     price_per_night = :price_per_night,
                     capacity_max = :capacity_max,
                     property_type = :property_type,
                     wifi = :wifi,
                     parking = :parking,
                     instant_booking = :instant_booking,
                     updated_at = NOW()
                     WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':address', $data['address']);
        $stmt->bindParam(':city', $data['city']);
        $stmt->bindParam(':price_per_night', $data['price_per_night']);
        $stmt->bindParam(':capacity_max', $data['capacity_max']);
        $stmt->bindParam(':property_type', $data['property_type']);
        $stmt->bindParam(':wifi', $data['wifi']);
        $stmt->bindParam(':parking', $data['parking']);
        $stmt->bindParam(':instant_booking', $data['instant_booking']);

        return $stmt->execute();
    }

    // Supprimer une annonce
    public function delete($id, $userId) {
        // Vérification de l'appartenance de l'annonce à l'utilisateur
        $query = "DELETE FROM " . $this->table . "
                  WHERE id = :id AND user_id = :user_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $userId);

        return $stmt->execute();
    }

    // Changer la disponibilité d'une annonce
    public function toggleAvailability($id, $userId) {
        $query = "UPDATE " . $this->table . "
                  SET is_available = NOT is_available
                  WHERE id = :id AND user_id = :user_id";
            
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $user_id);

        return $stmt->execute();
    }
}