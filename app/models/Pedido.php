<?php
require_once __DIR__ . "/../../config/db.php";

class Pedido {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    // Crear un pedido
    public function crearPedido($usuario_id, $total, $productos) {
        try {
            $this->pdo->beginTransaction();
    
            // Insertar el pedido en la base de datos
            $stmt = $this->pdo->prepare("INSERT INTO pedidos (usuario_id, total, estado) VALUES (:usuario_id, :total, 'pendiente')");
            $stmt->bindParam(":usuario_id", $usuario_id);
            $stmt->bindParam(":total", $total);
            $stmt->execute();
    
            $pedido_id = $this->pdo->lastInsertId();
    
            if (!$pedido_id) {
                throw new Exception("Error: No se pudo obtener el ID del pedido.");
            }
    
            echo "✅ Pedido insertado con ID: " . $pedido_id . "<br>";
    
            // Insertar los productos en pedidos_productos
            $stmt = $this->pdo->prepare("INSERT INTO pedidos_productos (pedido_id, producto_id, cantidad, precio_unitario) VALUES (:pedido_id, :producto_id, :cantidad, :precio_unitario)");
    
            foreach ($productos as $id => $producto) {
                $stmt->bindParam(":pedido_id", $pedido_id);
                $stmt->bindParam(":producto_id", $id);
                $stmt->bindParam(":cantidad", $producto['cantidad']);
                $stmt->bindParam(":precio_unitario", $producto['precio']);
                $stmt->execute();
                echo "✔️ Producto agregado al pedido: " . $id . "<br>";
            }
    
            $this->pdo->commit();
            return $pedido_id;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            echo "❌ Error al crear pedido: " . $e->getMessage();
            return false;
        }
    }
    

    // Obtener pedidos de un usuario
    public function obtenerPedidosPorUsuario($usuario_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM pedidos WHERE usuario_id = :usuario_id ORDER BY fecha DESC");
        $stmt->bindParam(":usuario_id", $usuario_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>
