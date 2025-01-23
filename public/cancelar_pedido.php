<?php
require_once __DIR__ . "/../app/controllers/PedidoController.php";

$pedidoController = new PedidoController();

if (isset($_GET['id'])) {
    $pedido_id = (int)$_GET['id'];
    $pedidoController->cancelarPedido($pedido_id);
} else {
    $_SESSION['mensaje'] = "ID de pedido no proporcionado.";
    $_SESSION['mensaje_tipo'] = "danger";
    header("Location: mis_pedidos.php");
    exit();
}

