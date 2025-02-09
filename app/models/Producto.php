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

    // Obtener productos con filtros (paginación, búsqueda y categoría)
    public function getFiltered($categoria_id = null, $precio_min = null, $precio_max = null, $offset = 0, $limit = 12) {
        $sql = "SELECT p.*, c.nombre as categoria_nombre 
                FROM productos p 
                LEFT JOIN categorias c ON p.categoria_id = c.id 
                WHERE p.activo = 1";

        $params = [];

        if (!empty($categoria_id)) {
            $sql .= " AND p.categoria_id = :categoria_id";
            $params[':categoria_id'] = $categoria_id;
        }

        if (!is_null($precio_min) && $precio_min !== '') {
            $sql .= " AND p.precio >= :precio_min";
            $params[':precio_min'] = $precio_min;
        }

        if (!is_null($precio_max) && $precio_max !== '') {
            $sql .= " AND p.precio <= :precio_max";
            $params[':precio_max'] = $precio_max;
        }

        $sql .= " ORDER BY p.id ASC LIMIT :offset, :limit";

        $stmt = $this->pdo->prepare($sql);

        foreach ($params as $key => &$val) {
            if ($key === ':offset' || $key === ':limit') {
                $stmt->bindValue($key, (int)$val, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($key, $val, PDO::PARAM_STR);
            }
        }

        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener total de productos filtrados (para la paginación)
    public function getTotalFiltered($categoria_id = null, $precio_min = null, $precio_max = null) {
        $sql = "SELECT COUNT(*) FROM productos p 
                LEFT JOIN categorias c ON p.categoria_id = c.id 
                WHERE p.activo = 1";

        $params = [];

        if (!empty($categoria_id)) {
            $sql .= " AND p.categoria_id = :categoria_id";
            $params[':categoria_id'] = $categoria_id;
        }

        if (!is_null($precio_min) && $precio_min !== '') {
            $sql .= " AND p.precio >= :precio_min";
            $params[':precio_min'] = $precio_min;
        }

        if (!is_null($precio_max) && $precio_max !== '') {
            $sql .= " AND p.precio <= :precio_max";
            $params[':precio_max'] = $precio_max;
        }

        $stmt = $this->pdo->prepare($sql);
        
        foreach ($params as $key => &$val) {
            $stmt->bindValue($key, $val);
        }

        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // Obtener el rango de precios de los productos activos
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

    // Actualizar un producto en la base de datos con validaciones
    public function actualizar($id, $datos) {
        $stmt = $this->pdo->prepare("
            UPDATE productos 
            SET nombre = :nombre, 
                precio = :precio, 
                stock = :stock, 
                categoria_id = :categoria_id, 
                descripcion = :descripcion, 
                imagen = :imagen, 
                activo = :activo 
            WHERE id = :id
        ");

        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->bindParam(":nombre", $datos["nombre"]);
        $stmt->bindParam(":precio", $datos["precio"]);
        $stmt->bindParam(":stock", $datos["stock"]);
        $stmt->bindParam(":categoria_id", $datos["categoria_id"]);
        $stmt->bindParam(":descripcion", $datos["descripcion"]);
        $stmt->bindParam(":imagen", $datos["imagen"]);
        $stmt->bindParam(":activo", $datos["activo"], PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Obtener productos inactivos (para administración)
    public function getInactivos() {
        $stmt = $this->pdo->prepare("
            SELECT p.*, c.nombre as categoria_nombre 
            FROM productos p 
            LEFT JOIN categorias c ON p.categoria_id = c.id 
            WHERE p.activo = 0
            ORDER BY p.updated_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Crear un nuevo producto con validaciones
    public function crear($datos) {
        $stmt = $this->pdo->prepare("
            INSERT INTO productos (nombre, descripcion, precio, stock, categoria_id, imagen, activo) 
            VALUES (:nombre, :descripcion, :precio, :stock, :categoria_id, :imagen, 1)
        ");

        $stmt->bindParam(":nombre", $datos['nombre']);
        $stmt->bindParam(":descripcion", $datos['descripcion']);
        $stmt->bindParam(":precio", $datos['precio']);
        $stmt->bindParam(":stock", $datos['stock']);
        $stmt->bindParam(":categoria_id", $datos['categoria_id']);
        $stmt->bindParam(":imagen", $datos['imagen']);

        try {
            if ($stmt->execute()) {
                return $this->pdo->lastInsertId();
            } else {
                throw new Exception("Error al ejecutar la consulta SQL.");
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
