<?php
require_once __DIR__ . "/../../config/db.php"; // Incluir la conexión a la base de datos

class Producto {
    private $pdo; // Conexión PDO

    public function __construct() {
        $this->pdo = Database::connect(); // Obtener conexión
    }

    // Obtener todos los productos
    public function getAll() {
        $stmt = $this->pdo->prepare("SELECT * FROM productos WHERE activo = 1 ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Obtener un producto por ID
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM productos WHERE id = :id AND activo = 1");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Obtener productos por categoría
    public function getByCategory($categoria_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM productos WHERE categoria_id = :categoria_id AND activo = 1");
        $stmt->bindParam(":categoria_id", $categoria_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Agregar un nuevo producto
    public function addProduct($nombre, $descripcion, $precio, $stock, $categoria_id, $imagen) {
        $stmt = $this->pdo->prepare("INSERT INTO productos (nombre, descripcion, precio, stock, categoria_id, imagen) 
                                     VALUES (:nombre, :descripcion, :precio, :stock, :categoria_id, :imagen)");
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":descripcion", $descripcion);
        $stmt->bindParam(":precio", $precio);
        $stmt->bindParam(":stock", $stock);
        $stmt->bindParam(":categoria_id", $categoria_id);
        $stmt->bindParam(":imagen", $imagen);
        return $stmt->execute();
    }

    // Actualizar un producto existente
    public function updateProduct($id, $nombre, $descripcion, $precio, $stock, $categoria_id, $imagen) {
        $stmt = $this->pdo->prepare("UPDATE productos SET nombre = :nombre, descripcion = :descripcion, 
                                     precio = :precio, stock = :stock, categoria_id = :categoria_id, imagen = :imagen 
                                     WHERE id = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":descripcion", $descripcion);
        $stmt->bindParam(":precio", $precio);
        $stmt->bindParam(":stock", $stock);
        $stmt->bindParam(":categoria_id", $categoria_id);
        $stmt->bindParam(":imagen", $imagen);
        return $stmt->execute();
    }

    // Eliminar un producto (baja lógica)
    public function deleteProduct($id) {
        $stmt = $this->pdo->prepare("UPDATE productos SET activo = 0 WHERE id = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>
