<?php
require_once __DIR__ . "/../models/Pedido.php";
require_once __DIR__ . "/../models/Carrito.php";
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class PedidoController {
    private $pedidoModel;
    private $carrito;

    public function __construct() {
        $this->pedidoModel = new Pedido();
        $this->carrito = new Carrito();
    }

    // Procesar la compra
    public function checkout() {
        if (!isset($_SESSION['usuario'])) {
            header("Location: login.php");
            exit();
        }
    
        $usuario_id = $_SESSION['usuario']['id'];
        $productos = $this->carrito->obtenerCarrito();
        $total = $this->carrito->obtenerTotal();
    
        if (empty($productos)) {
            echo "❌ Error: El carrito está vacío.";
            return;
        }
    
        echo "➡️ Usuario ID: " . $usuario_id . "<br>";
        echo "➡️ Total del pedido: $" . $total . "<br>";
        echo "➡️ Productos en el carrito: <pre>" . print_r($productos, true) . "</pre><br>";
    
        // Crear pedido en la base de datos
        $pedido_id = $this->pedidoModel->crearPedido($usuario_id, $total, $productos);
    
        if ($pedido_id) {
            echo "✅ Pedido registrado con ID: " . $pedido_id;
            $this->carrito->vaciarCarrito();
            header("Location: pedido_confirmado.php?pedido_id=" . $pedido_id);
            exit();
        } else {
            echo "❌ Error al registrar el pedido en la base de datos.";
        }
    }
    

    // Mostrar los pedidos del usuario
    public function misPedidos() {
        if (!isset($_SESSION['usuario'])) {
            header("Location: login.php");
            exit();
        }

        $usuario_id = $_SESSION['usuario']['id'];
        $pedidos = $this->pedidoModel->obtenerPedidosPorUsuario($usuario_id);
        require_once __DIR__ . "/../views/mis_pedidos.php";
    }
}
?>
