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
            $_SESSION['error'] = "El carrito está vacío.";
            header("Location: carrito.php");
            exit();
        }
    
        try {
            $pedido_id = $this->pedidoModel->crearPedido($usuario_id, $total, $productos);
            $this->carrito->vaciarCarrito();
            $_SESSION['success'] = "Pedido registrado con éxito.";
            header("Location: pedido_confirmado.php?pedido_id=" . $pedido_id);
            exit();
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al registrar el pedido: " . $e->getMessage();
            header("Location: carrito.php");
            exit();
        }
    }

    // Mostrar los pedidos del usuario
    public function misPedidos() {
        if (!isset($_SESSION['usuario'])) {
            header("Location: login.php");
            exit();
        }

        $usuario_id = $_SESSION['usuario']['id'];
        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        $porPagina = 10;

        $pedidos = $this->pedidoModel->obtenerHistorialCompras($usuario_id, $pagina, $porPagina);
        $totalPedidos = $this->pedidoModel->obtenerTotalPedidos($usuario_id);
        $totalPaginas = ceil($totalPedidos / $porPagina);

        return [
            'pedidos' => $pedidos,
            'totalPaginas' => $totalPaginas,
            'paginaActual' => $pagina
        ];
    } 

    // Ver detalles de un pedido específico
    public function verPedido($pedido_id) {
        if (!isset($_SESSION['usuario'])) {
            header("Location: login.php");
            exit();
        }
    
        $usuario_id = $_SESSION['usuario']['id'];
        $pedido = $this->pedidoModel->obtenerPedido($pedido_id);
    
        if (!$pedido || empty($pedido) || $pedido[0]['usuario_id'] != $usuario_id) {
            $_SESSION['error'] = "Pedido no encontrado o no tienes permiso para verlo.";
            header("Location: mis_pedidos.php");
            exit();
        }
    
        // Guardar la información del pedido en sesión
        $_SESSION['pedido_detalle'] = $pedido;
    
        header("Location: detalle_pedido.php");
        exit();
    }
    
    

    // Cancelar un pedido
    public function cancelarPedido($pedido_id) {
        if (!isset($_SESSION['usuario'])) {
            header("Location: login.php");
            exit();
        }

        $usuario_id = $_SESSION['usuario']['id'];
        $pedido = $this->pedidoModel->obtenerPedido($pedido_id);

        if (!$pedido || $pedido[0]['usuario_id'] != $usuario_id) {
            $_SESSION['mensaje'] = "Pedido no encontrado o no tienes permiso para cancelarlo.";
            $_SESSION['mensaje_tipo'] = "danger";
            header("Location: mis_pedidos.php");
            exit();
        }

        try {
            if ($this->pedidoModel->cancelarPedido($pedido_id)) {
                $_SESSION['mensaje'] = "Pedido cancelado con éxito.";
                $_SESSION['mensaje_tipo'] = "success";
            } else {
                $_SESSION['mensaje'] = "No se pudo cancelar el pedido.";
                $_SESSION['mensaje_tipo'] = "warning";
            }
        } catch (Exception $e) {
            $_SESSION['mensaje'] = "Error al cancelar el pedido: " . $e->getMessage();
            $_SESSION['mensaje_tipo'] = "danger";
        }

        header("Location: mis_pedidos.php");
        exit();
    }

    // Actualizar el estado de un pedido (para uso administrativo)
    public function actualizarEstadoPedido($pedido_id, $nuevo_estado) {
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] != 'admin') {
            header("Location: login.php");
            exit();
        }

        try {
            if ($this->pedidoModel->actualizarEstado($pedido_id, $nuevo_estado)) {
                $_SESSION['success'] = "Estado del pedido actualizado con éxito.";
            } else {
                $_SESSION['error'] = "No se pudo actualizar el estado del pedido.";
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al actualizar el estado del pedido: " . $e->getMessage();
        }

        header("Location: admin_pedidos.php");
        exit();
    }

    public function getEstadoBadgeClass($estado) {
        switch ($estado) {
            case 'pendiente':
                return 'warning';
            case 'procesando':
                return 'info';
            case 'enviado':
                return 'primary';
            case 'entregado':
                return 'success';
            case 'cancelado':
                return 'danger';
            default:
                return 'secondary';
        }
    }
}
?>

