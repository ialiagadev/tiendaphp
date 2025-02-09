<?php
require_once __DIR__ . "/../../config/db.php";

class Pedido {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    public function crearPedido($usuario_id, $total, $productos) {
        try {
            $this->pdo->beginTransaction();
    
            $stmt = $this->pdo->prepare("INSERT INTO pedidos (usuario_id, total, estado) VALUES (:usuario_id, :total, 'pendiente')");
            $stmt->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
            $stmt->bindParam(":total", $total, PDO::PARAM_STR);
            $stmt->execute();
    
            $pedido_id = $this->pdo->lastInsertId();
    
            if (!$pedido_id) {
                throw new Exception("Error: No se pudo obtener el ID del pedido.");
            }
    
            $stmt = $this->pdo->prepare("INSERT INTO pedidos_productos (pedido_id, producto_id, cantidad, precio_unitario) VALUES (:pedido_id, :producto_id, :cantidad, :precio_unitario)");
    
            foreach ($productos as $id => $producto) {
                $stmt->bindParam(":pedido_id", $pedido_id, PDO::PARAM_INT);
                $stmt->bindParam(":producto_id", $id, PDO::PARAM_INT);
                $stmt->bindParam(":cantidad", $producto['cantidad'], PDO::PARAM_INT);
                $stmt->bindParam(":precio_unitario", $producto['precio'], PDO::PARAM_STR);
                $stmt->execute();
            }
    
            $this->pdo->commit();
            return $pedido_id;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw new Exception("Error al crear pedido: " . $e->getMessage());
        }
    }

    public function obtenerPedidosPorUsuario($usuario_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM pedidos WHERE usuario_id = :usuario_id ORDER BY fecha DESC");
        $stmt->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPedido($pedido_id) {
        $stmt = $this->pdo->prepare("
            SELECT p.*, pp.producto_id, pp.cantidad, pp.precio_unitario, pr.nombre as nombre_producto
            FROM pedidos p 
            LEFT JOIN pedidos_productos pp ON p.id = pp.pedido_id 
            LEFT JOIN productos pr ON pp.producto_id = pr.id
            WHERE p.id = :pedido_id
        ");
        $stmt->bindParam(":pedido_id", $pedido_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizarEstado($pedido_id, $nuevo_estado) {
        $estados_validos = ['pendiente', 'procesando', 'enviado', 'entregado', 'cancelado'];
        if (!in_array($nuevo_estado, $estados_validos)) {
            throw new Exception("Estado no válido");
        }

        $stmt = $this->pdo->prepare("UPDATE pedidos SET estado = :nuevo_estado WHERE id = :pedido_id");
        $stmt->bindParam(":nuevo_estado", $nuevo_estado, PDO::PARAM_STR);
        $stmt->bindParam(":pedido_id", $pedido_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function puedeSerCancelado($pedido_id) {
        $stmt = $this->pdo->prepare("SELECT estado FROM pedidos WHERE id = :pedido_id");
        $stmt->bindParam(":pedido_id", $pedido_id, PDO::PARAM_INT);
        $stmt->execute();
        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$pedido) {
            throw new Exception("Pedido no encontrado");
        }

        return in_array($pedido['estado'], ['pendiente', 'procesando']);
    }

    public function cancelarPedido($pedido_id) {
        if (!$this->puedeSerCancelado($pedido_id)) {
            throw new Exception("Este pedido no puede ser cancelado");
        }

        return $this->actualizarEstado($pedido_id, 'cancelado');
    }

    public function obtenerHistorialCompras($usuario_id, $pagina = 1, $porPagina = 10) {
        $offset = ($pagina - 1) * $porPagina;
        
        $stmt = $this->pdo->prepare("
            SELECT p.*, COUNT(pp.producto_id) as num_productos 
            FROM pedidos p 
            LEFT JOIN pedidos_productos pp ON p.id = pp.pedido_id 
            WHERE p.usuario_id = :usuario_id 
            GROUP BY p.id 
            ORDER BY p.fecha DESC 
            LIMIT :offset, :porPagina
        ");
        $stmt->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        $stmt->bindParam(":porPagina", $porPagina, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerTotalPedidos($usuario_id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM pedidos WHERE usuario_id = :usuario_id");
        $stmt->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    public function obtenerProductosPedido($pedido_id) {
        $stmt = $this->pdo->prepare("
            SELECT pp.pedido_id, pr.nombre AS nombre_producto, pp.cantidad, pp.precio_unitario
            FROM pedidos_productos pp
            INNER JOIN productos pr ON pp.producto_id = pr.id
            WHERE pp.pedido_id = :pedido_id
        ");
        $stmt->bindParam(":pedido_id", $pedido_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getPedidoById($id) {
        $stmt = $this->pdo->prepare("
            SELECT p.*, u.nombre as cliente_nombre 
            FROM pedidos p 
            JOIN usuarios u ON p.usuario_id = u.id 
            WHERE p.id = :id
        ");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($pedido) {
            $stmt = $this->pdo->prepare("
                SELECT pr.nombre, pr.precio, dp.cantidad 
                FROM detalle_pedido dp 
                JOIN productos pr ON dp.producto_id = pr.id 
                WHERE dp.pedido_id = :id
            ");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $pedido['productos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    
        return $pedido;
    }
    
}
?>

