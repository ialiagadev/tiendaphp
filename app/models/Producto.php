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
            ORDER BY p.id DESC
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
            ORDER BY p.id DESC
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

    public function getFiltered($categoria_id = null, $precio_min = null, $precio_max = null, $offset = 0, $limit = 12) {
        $sql = "SELECT p.*, c.nombre as categoria_nombre 
                FROM productos p 
                LEFT JOIN categorias c ON p.categoria_id = c.id 
                WHERE p.activo = 1";
        $params = [];

        if ($categoria_id) {
            $sql .= " AND p.categoria_id = :categoria_id";
            $params[':categoria_id'] = $categoria_id;
        }

        if ($precio_min !== null) {
            $sql .= " AND p.precio >= :precio_min";
            $params[':precio_min'] = $precio_min;
        }

        if ($precio_max !== null) {
            $sql .= " AND p.precio <= :precio_max";
            $params[':precio_max'] = $precio_max;
        }

        $sql .= " ORDER BY p.id ASC LIMIT :offset, :limit";
        $params[':offset'] = $offset;
        $params[':limit'] = $limit;

        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $key => &$val) {
            $stmt->bindParam($key, $val);
        }
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalFiltered($categoria_id = null, $precio_min = null, $precio_max = null) {
        $sql = "SELECT COUNT(*) FROM productos WHERE activo = 1";
        $params = [];

        if ($categoria_id) {
            $sql .= " AND categoria_id = :categoria_id";
            $params[':categoria_id'] = $categoria_id;
        }

        if ($precio_min !== null) {
            $sql .= " AND precio >= :precio_min";
            $params[':precio_min'] = $precio_min;
        }

        if ($precio_max !== null) {
            $sql .= " AND precio <= :precio_max";
            $params[':precio_max'] = $precio_max;
        }

        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $key => &$val) {
            $stmt->bindParam($key, $val);
        }
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    public function getRangoPreciosGlobal() {
        $stmt = $this->pdo->query("SELECT MIN(precio) as min_precio, MAX(precio) as max_precio FROM productos WHERE activo = 1");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

        // Baja lógica (eliminar producto)
        public function eliminar($id) {
            $stmt = $this->pdo->prepare("UPDATE productos SET activo = 0 WHERE id = :id");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            return $stmt->execute();
        }
    
        // Reactivar un producto eliminado
        public function reactivar($id) {
            $stmt = $this->pdo->prepare("UPDATE productos SET activo = 1 WHERE id = :id");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            return $stmt->execute();
        }
    
        // Obtener productos inactivos (para administración)
        public function getInactivos() {
            $stmt = $this->pdo->prepare("SELECT p.*, c.nombre as categoria_nombre 
                                         FROM productos p 
                                         LEFT JOIN categorias c ON p.categoria_id = c.id 
                                         WHERE p.activo = 0
                                         ORDER BY p.updated_at DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    
}

