<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Pedidos - Tienda Online</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1;
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
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php"><i class="fas fa-store me-2"></i>Tienda Online</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="carrito.php"><i class="fas fa-shopping-cart me-1"></i>Carrito</a></li>
                    <li class="nav-item"><span class="nav-link"><i class="fas fa-user me-1"></i>Hola, <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?></span></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt me-1"></i>Cerrar Sesión</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container mt-4">
        <h1 class="text-center mb-4">Mis Pedidos</h1>

        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="alert alert-<?= $_SESSION['mensaje_tipo'] ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['mensaje'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['mensaje'], $_SESSION['mensaje_tipo']); ?>
        <?php endif; ?>

        <?php if (empty($pedidos)): ?>
            <div class="alert alert-info" role="alert">
                No tienes pedidos aún. <a href="index.php" class="alert-link">¡Empieza a comprar!</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID Pedido</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidos as $pedido): ?>
                            <tr>
                                <td>#<?= htmlspecialchars($pedido['id']) ?></td>
                                <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($pedido['fecha']))) ?></td>
                                <td>$<?= number_format($pedido['total'], 2) ?></td>
                                <td>
                                    <span class="badge bg-<?= $this->getEstadoBadgeClass($pedido['estado']) ?>">
                                        <?= ucfirst(htmlspecialchars($pedido['estado'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="detalle_pedido.php?id=<?= $pedido['id'] ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                    <?php if ($pedido['estado'] == 'pendiente' || $pedido['estado'] == 'procesando'): ?>
                                        <a href="cancelar_pedido.php?id=<?= $pedido['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que deseas cancelar este pedido?');">
                                            <i class="fas fa-times"></i> Cancelar
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <?php if ($totalPaginas > 1): ?>
                <nav aria-label="Navegación de páginas">
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
    </main>

    <footer class="footer mt-auto py-3">
        <div class="container">
            <span>&copy; <?= date('Y') ?> Tienda Online. Todos los derechos reservados.</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

