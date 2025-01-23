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
        .product-image {
            max-height: 400px;
            object-fit: cover;
            width: 100%;
            border-radius: 8px 8px 0 0;
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
                    <img src="<?= htmlspecialchars($producto['imagen']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>" class="product-image">
                    <div class="card-body">
                        <h1 class="card-title"><?= htmlspecialchars($producto['nombre']) ?></h1>
                        <p class="card-text"><?= nl2br(htmlspecialchars($producto['descripcion'])) ?></p>
                        <p class="card-text"><strong>Precio:</strong> $<?= number_format($producto['precio'], 2) ?></p>
                        <p class="card-text"><strong>Stock:</strong> <?= htmlspecialchars($producto['stock']) ?> unidades</p>
                        <div class="d-grid gap-2">
                            <a href="../public/carrito.php?accion=agregar&id=<?= $producto['id'] ?>" class="btn btn-primary btn-lg">
                                <i class="fas fa-cart-plus me-2"></i>Añadir al Carrito
                            </a>
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
            <p class="mb-0">&copy; 2023 Tienda Online. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>