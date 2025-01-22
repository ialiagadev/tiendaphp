<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras - Tienda Online</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .card {
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,.1);
        }
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .table th {
            background-color: #007bff;
            color: white;
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
                    <li class="nav-item"><a class="nav-link active" href="carrito.php"><i class="fas fa-shopping-cart me-1"></i>Carrito</a></li>
                    <?php if (isset($_SESSION['usuario'])): ?>
                        <li class="nav-item"><span class="nav-link"><i class="fas fa-user me-1"></i>Hola, <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?></span></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt me-1"></i>Cerrar Sesión</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php"><i class="fas fa-sign-in-alt me-1"></i>Iniciar Sesión</a></li>
                        <li class="nav-item"><a class="nav-link" href="registro.php"><i class="fas fa-user-plus me-1"></i>Registrarse</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <h1 class="text-center mb-4"><i class="fas fa-shopping-cart me-2"></i>Carrito de Compras</h1>

        <?php if (empty($productos)): ?>
            <div class="alert alert-warning text-center">
                <i class="fas fa-exclamation-triangle me-2"></i>No hay productos en el carrito.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos as $id => $producto): ?>
                            <tr>
                                <td><?= htmlspecialchars($producto['nombre']) ?></td>
                                <td>$<?= number_format($producto['precio'], 2) ?></td>
                                <td>
                                    <div class="input-group" style="max-width: 120px;">
                                        <button class="btn btn-outline-secondary btn-sm" type="button" onclick="updateQuantity(<?= $id ?>, -1)">-</button>
                                        <input type="text" class="form-control form-control-sm text-center" value="<?= htmlspecialchars($producto['cantidad']) ?>" id="quantity-<?= $id ?>" readonly>
                                        <button class="btn btn-outline-secondary btn-sm" type="button" onclick="updateQuantity(<?= $id ?>, 1)">+</button>
                                    </div>
                                </td>
                                <td>$<?= number_format($producto['precio'] * $producto['cantidad'], 2) ?></td>
                                <td>
                                    <a href="carrito.php?accion=eliminar&id=<?= $id ?>" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i> Eliminar
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="card mt-4">
                <div class="card-body">
                    <h3 class="card-title">Total: $<?= number_format($total, 2) ?></h3>
                    <div class="d-flex justify-content-between mt-3">
                        <a href="carrito.php?accion=vaciar" class="btn btn-warning">
                            <i class="fas fa-trash me-2"></i>Vaciar Carrito
                        </a>
                        <form action="checkout.php" method="POST" class="d-inline-block">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check me-2"></i>Finalizar Compra
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="mt-4 text-center">
            <a href="index.php" class="btn btn-primary">
                <i class="fas fa-arrow-left me-2"></i>Seguir Comprando
            </a>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <div class="container">
            <p>&copy; 2023 Tienda Online. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateQuantity(productId, change) {
            const quantityInput = document.getElementById(`quantity-${productId}`);
            let newQuantity = parseInt(quantityInput.value) + change;
            if (newQuantity > 0) {
                quantityInput.value = newQuantity;
                window.location.href = `carrito.php?accion=actualizar&id=${productId}&cantidad=${newQuantity}`;
            }
        }
    </script>
</body>
</html>

