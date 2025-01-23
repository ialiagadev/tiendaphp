<?php
require_once __DIR__ . "/../../config/db.php";

class Categoria {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    // Obtener todas las categorías activas
    public function getAll() {
        $stmt = $this->pdo->prepare("SELECT id, nombre FROM categorias WHERE activo = 1");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener una categoría por ID
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT id, nombre FROM categorias WHERE id = :id AND activo = 1");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
