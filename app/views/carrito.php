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
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        main {
            flex: 1 0 auto;
        }
        .card {
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            border: none;
            border-radius: 10px;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,.1);
        }
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        }
        .table th {
            background-color: #f8f9fa;
            color: #495057;
            border: none;
            font-weight: 600;
        }
        .table td {
            vertical-align: middle;
        }
        .btn {
            border-radius: 5px;
            padding: 8px 16px;
            transition: all 0.2s ease;
            font-weight: 500;
        }
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .btn-outline-secondary {
            color: #6c757d;
            border-color: #ced4da;
        }
        .btn-outline-secondary:hover {
            color: #495057;
            background-color: #e9ecef;
        }
        .footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 1rem 0;
            margin-top: 2rem;
        }
        .quantity-input {
            max-width: 60px;
            text-align: center;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 0.9rem;
        }
        .quantity-btn {
            width: 30px;
            height: 30px;
            padding: 0;
            line-height: 30px;
            font-size: 14px;
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

    <main class="container my-5">
        <h1 class="text-center mb-4">Carrito de Compras</h1>

        <?php if (empty($productos)): ?>
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i>No hay productos en el carrito.
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
                                    <div class="input-group">
                                        <button class="btn btn-outline-secondary quantity-btn" type="button" onclick="updateQuantity(<?= $id ?>, -1)">-</button>
                                        <input type="text" class="form-control quantity-input" value="<?= htmlspecialchars($producto['cantidad']) ?>" id="quantity-<?= $id ?>" readonly>
                                        <button class="btn btn-outline-secondary quantity-btn" type="button" onclick="updateQuantity(<?= $id ?>, 1)">+</button>
                                    </div>
                                </td>
                                <td>$<?= number_format($producto['precio'] * $producto['cantidad'], 2) ?></td>
                                <td>
                                    <a href="carrito.php?accion=eliminar&id=<?= $id ?>" class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="card mt-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">Total: $<?= number_format($total, 2) ?></h3>
                        <div>
                            <a href="carrito.php?accion=vaciar" class="btn btn-outline-warning me-2">
                                <i class="fas fa-trash me-1"></i>Vaciar Carrito
                            </a>
                            <form action="checkout.php" method="POST" class="d-inline-block">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-check me-1"></i>Finalizar Compra
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="mt-4 text-center">
            <a href="index.php" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-1"></i>Seguir Comprando
            </a>
            <form action="/TIENDA/public/stripe_checkout.php" method="POST">
</form>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p class="mb-0">&copy; 2023 Tienda Online. Todos los derechos reservados.</p>
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