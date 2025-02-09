<?php
require_once __DIR__ . "/../app/controllers/PedidoController.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$pedidoController = new PedidoController();

// Verificar que se haya recibido un ID válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Error: ID de pedido no válido.");
}

$pedido_id = intval($_GET['id']);
$pedido = $pedidoController->verPedido($pedido_id);

// Verificar que el pedido exista
if (!$pedido) {
    die("Error: Pedido no encontrado.");
}

$productos = $pedidoController->pedidoModel->obtenerPedido($pedido_id);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Pedido #<?= htmlspecialchars($pedido_id) ?> - Tienda Online</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 40px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0,0,0,0.1);
        }
        .footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 1rem 0;
            margin-top: 2rem;
        }
    </style>
</head>
<body>

<?php include_once "../app/components/navbar.php"; ?>

<div class="container">
    <h2 class="mb-4">Detalle del Pedido #<?= htmlspecialchars($pedido_id) ?></h2>

    <div class="card p-4">
        <h4>Información del Pedido</h4>
        <p><strong>Fecha:</strong> <?= htmlspecialchars($pedido[0]['fecha']) ?></p>
        <p><strong>Estado:</strong> <span class="badge bg-<?= $pedidoController->getEstadoBadgeClass($pedido[0]['estado']) ?>">
            <?= ucfirst($pedido[0]['estado']) ?></span>
        </p>
        <p><strong>Total:</strong> $<?= number_format($pedido[0]['total'], 2) ?></p>

        <h4 class="mt-4">Productos Comprados</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?= htmlspecialchars($producto['nombre_producto']) ?></td>
                        <td><?= $producto['cantidad'] ?></td>
                        <td>$<?= number_format($producto['precio_unitario'], 2) ?></td>
                        <td>$<?= number_format($producto['cantidad'] * $producto['precio_unitario'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <a href="mis_pedidos.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver a Mis Pedidos
        </a>
    </div>
</div>

<footer class="footer mt-auto">
    <div class="container">
        <p class="mb-0">&copy; 2023 Tienda Online. Todos los derechos reservados.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
