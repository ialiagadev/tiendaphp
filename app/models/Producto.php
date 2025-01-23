<?php
require_once __DIR__ . "/../../config/db.php";

class Producto {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    // Obtener todos los productos activos con su categoría
    public function getAll() {
        $stmt = $this->pdo->prepare("
            SELECT p.*, c.nombre as categoria_nombre 
            FROM productos p 
            LEFT JOIN categorias c ON p.categoria_id = c.id 
            WHERE p.activo = 1
            ORDER BY p.created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener productos por categoría
    public function getByCategory($categoria_id) {
        $stmt = $this->pdo->prepare("
            SELECT p.*, c.nombre as categoria_nombre 
            FROM productos p 
            LEFT JOIN categorias c ON p.categoria_id = c.id 
            WHERE p.categoria_id = :categoria_id AND p.activo = 1
            ORDER BY p.created_at DESC
        ");
        $stmt->bindParam(":categoria_id", $categoria_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un producto por ID
    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT p.*, c.nombre as categoria_nombre 
            FROM productos p 
            LEFT JOIN categorias c ON p.categoria_id = c.id 
            WHERE p.id = :id AND p.activo = 1
        ");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
