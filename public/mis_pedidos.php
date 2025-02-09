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
$resultado = $pedidoController->misPedidos();

$pedidos = $resultado['pedidos'];
$totalPaginas = $resultado['totalPaginas'];
$paginaActual = $resultado['paginaActual'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Pedidos - Tienda Online</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Mis Pedidos</h1>
        
        <?php if (empty($pedidos)): ?>
            <div class="alert alert-info">
                No tienes pedidos aún. <a href="index.php" class="alert-link">¡Empieza a comprar!</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID Pedido</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Productos Comprados</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidos as $pedido): ?>
                            <tr>
                                <td><?= $pedido['id'] ?></td>
                                <td><?= $pedido['fecha'] ?></td>
                                <td>$<?= number_format($pedido['total'], 2) ?></td>
                                <td><?= ucfirst($pedido['estado']) ?></td>
                                <td>
                                    <ul>
                                        <?php 
                                        $productos = $pedidoController->obtenerProductosPorPedido($pedido['id']);
                                        if (!empty($productos)): 
                                            foreach ($productos as $producto): ?>
                                                <li>
                                                    <?= htmlspecialchars($producto['nombre_producto']) ?> - 
                                                    <?= $producto['cantidad'] ?> x $<?= number_format($producto['precio_unitario'], 2) ?>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <li>No hay productos en este pedido.</li>
                                        <?php endif; ?>
                                    </ul>
                                </td>
                                <td>
                                    <?php if ($pedido['estado'] == 'pendiente' || $pedido['estado'] == 'procesando'): ?>
                                        <a href="cancelar_pedido.php?id=<?= $pedido['id'] ?>" class="btn btn-danger btn-sm" 
                                           onclick="return confirm('¿Estás seguro de que deseas cancelar este pedido?');">
                                            Cancelar
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">No disponible</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <?php if ($totalPaginas > 1): ?>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                            <li class="page-item <?= $i == $paginaActual ? 'active' : '' ?>">
                                <a class="page-link" href="?pagina=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
