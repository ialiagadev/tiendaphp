<?php
require_once __DIR__ . "/../app/controllers/ProductoController.php";

$controller = new ProductoController();

// 📌 Verifica si hay un ID en la URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("<div class='container mt-5 alert alert-danger'>Error: No se ha recibido el ID del producto.</div>");
}

// 📌 Obtener el producto
$producto = $controller->obtenerProducto($_GET['id']);

// 📌 Si el producto no existe, mostrar mensaje de error
if (!$producto) {
    die("<div class='container mt-5 alert alert-warning'>Error: Producto no encontrado.</div>");
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
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f8f9fa;
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
        .product-image-container {
            position: relative;
            width: 100%;
            padding-top: 75%;
            overflow: hidden;
            border-radius: 8px;
        }
        .product-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
            background-color: #f8f9fa;
        }
        .card {
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            border: none;
            border-radius: 8px;
            overflow: hidden;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,.1);
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

<main class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="product-image-container">
                            <img src="<?= htmlspecialchars($producto['imagen']) ?>" 
                                 alt="<?= htmlspecialchars($producto['nombre']) ?>" 
                                 class="product-image">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h1 class="mb-3"><?= htmlspecialchars($producto['nombre']) ?></h1>
                        <p class="lead mb-4"><?= nl2br(htmlspecialchars($producto['descripcion'])) ?></p>
                        <p class="h3 text-primary mb-4">$<?= number_format($producto['precio'], 2) ?></p>
                        <p><strong>Categoría:</strong> <?= htmlspecialchars($producto['categoria_nombre']) ?></p>
                        <p><strong>Stock:</strong> <?= $producto['stock'] ?> unidades</p>

                        <?php if ($producto['stock'] > 0): ?>
                            <form action="../public/carrito.php" method="GET" class="mb-4">
                                <input type="hidden" name="accion" value="agregar">
                                <input type="hidden" name="id" value="<?= $producto['id'] ?>">
                                <div class="mb-3">
                                    <label for="cantidad" class="form-label">Cantidad:</label>
                                    <input type="number" id="cantidad" name="cantidad" value="1" min="1" 
                                           max="<?= $producto['stock'] ?>" class="form-control" style="width: 100px;">
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

<footer class="footer mt-auto">
    <div class="container">
        <p class="mb-0">&copy; <?= date("Y") ?> Tienda Online. Todos los derechos reservados.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
