<?php
require_once "../app/controllers/PedidoController.php";

$controller = new PedidoController();

// Si el usuario envió el formulario, procesar el pedido
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $controller->checkout();
} else {
    $controller->misPedidos(); // Mostrar pedidos previos si no se envió el formulario
}
?>
