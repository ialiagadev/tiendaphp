<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . "/../app/controllers/PedidoController.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['pedido_detalle'])) {
    die("Error: No se encontró la información del pedido.");
}

$pedido = $_SESSION['pedido_detalle'];
unset($_SESSION['pedido_detalle']); // Limpiar la sesión después de obtener los datos
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Pedido #<?= htmlspecialchars($pedido[0]['pedido_id']) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Detalle del Pedido #<?= htmlspecialchars($pedido[0]['pedido_id']) ?></h1>
        
        <p><strong>Fecha:</strong> <?= htmlspecialchars($pedido[0]['fecha']) ?></p>
        <p><strong>Total:</strong> $<?= number_format($pedido[0]['total'], 2) ?></p>
        <p><strong>Estado:</strong> <?= ucfirst(htmlspecialchars($pedido[0]['estado'])) ?></p>

        <h3 class="mt-4">Productos</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedido as $producto): ?>
                    <tr>
                        <td><?= htmlspecialchars($producto['nombre_producto']) ?></td>
                        <td><?= htmlspecialchars($producto['cantidad']) ?></td>
                        <td>$<?= number_format($producto['precio_unitario'], 2) ?></td>
                        <td>$<?= number_format($producto['cantidad'] * $producto['precio_unitario'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="mis_pedidos.php" class="btn btn-secondary">Volver a Mis Pedidos</a>
    </div>
</body>
</html>
