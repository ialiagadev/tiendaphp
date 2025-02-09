<?php
require_once __DIR__ . "/../app/controllers/ProductoController.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$controller = new ProductoController();
$producto = $controller->productoModel->getById($_GET['id']);

if (!$producto) {
    $_SESSION['error'] = "El producto no existe.";
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($producto['nombre']) ?> - Tienda Online</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include_once "../app/components/navbar.php"; ?>

    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="product-image-container">
                                <img src="<?= htmlspecialchars($producto['imagen']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>" class="img-fluid">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h1 class="mb-3"><?= htmlspecialchars($producto['nombre']) ?></h1>
                            <p class="lead mb-4"><?= nl2br(htmlspecialchars($producto['descripcion'])) ?></p>
                            <p class="h3 text-primary mb-4">$<?= number_format($producto['precio'], 2) ?></p>
                            <p><strong>Categoría:</strong> <?= htmlspecialchars($producto['categoria_nombre']) ?></p>
                            <p><strong>Stock:</strong> <?= $producto['stock'] ?> unidades</p>

                            <?php if ($producto['stock'] > 0): ?>
                                <form action="carrito.php" method="GET" class="mb-4">
                                    <input type="hidden" name="accion" value="agregar">
                                    <input type="hidden" name="id" value="<?= $producto['id'] ?>">
                                    <div class="mb-3">
                                        <label for="cantidad" class="form-label">Cantidad:</label>
                                        <input type="number" id="cantidad" name="cantidad" value="1" min="1" max="<?= $producto['stock'] ?>" class="form-control" style="width: 100px;">
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-cart-plus me-2"></i>Añadir al Carrito
                                    </button>
                                </form>
                            <?php else: ?>
                                <button class="btn btn-secondary btn-lg mb-4" disabled>
                                    <i class="fas fa-times-circle me-2"></i>Agotado
                                </button>
                            <?php endif; ?>

                            <a href="index.php" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Volver a la Tienda
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
