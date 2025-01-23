<?php
require_once "../app/controllers/ProductoController.php";
session_start();

$controller = new ProductoController();

// Obtener categoría seleccionada si existe
$categoria_id = isset($_GET['categoria']) ? intval($_GET['categoria']) : null;
$data = $controller->obtenerProductosConCategorias($categoria_id);
$productos = $data['productos'];
$categorias = $data['categorias'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Online</title>
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
        .card {
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        .btn-add-to-cart {
            transition: background-color 0.3s ease-in-out;
        }
        .btn-add-to-cart:hover {
            background-color: #0056b3;
        }
        .stock-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1;
        }
        .destacado-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 1;
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

    <main class="container my-4">
        <h1 class="text-center mb-4">Bienvenido a la Tienda Online</h1>
        
        <div class="row mb-4">
            <div class="col-md-4">
                <input type="text" id="busqueda" class="form-control" placeholder="Buscar productos...">
            </div>
            <div class="col-md-4">
                <select id="categoria" class="form-select">
                    <option value="">Todas las categorías</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?= $categoria['id'] ?>" <?= $categoria_id == $categoria['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($categoria['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div id="productos-container" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($productos as $producto): ?>
                <div class="col producto-item" data-nombre="<?= strtolower(htmlspecialchars($producto['nombre'])) ?>">
                    <div class="card h-100">
                        <?php if ($producto['destacado']): ?>
                            <div class="destacado-badge">
                                <span class="badge bg-warning text-dark">Destacado</span>
                            </div>
                        <?php endif; ?>
                        <?php if ($producto['stock'] < 10): ?>
                            <div class="stock-badge">
                                <span class="badge bg-danger">¡Últimas <?= $producto['stock'] ?> unidades!</span>
                            </div>
                        <?php endif; ?>
                        <img src="<?= htmlspecialchars($producto['imagen']) ?>" class="card-img-top" alt="<?= htmlspecialchars($producto['nombre']) ?>">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($producto['nombre']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($producto['descripcion']) ?></p>
                            <p class="card-text">
                                <span class="h5 text-primary">$<?= number_format($producto['precio'], 2) ?></span>
                            </p>
                            <p class="card-text">
                                <small class="text-muted">Categoría: <?= htmlspecialchars($producto['categoria_nombre']) ?></small>
                            </p>
                            <div class="mt-auto d-flex flex-column gap-2">
                                <a href="producto.php?id=<?= $producto['id'] ?>" class="btn btn-outline-primary">
                                    <i class="fas fa-info-circle me-2"></i>Ver Detalles
                                </a>
                                <?php if ($producto['stock'] > 0): ?>
                                    <a href="carrito.php?accion=agregar&id=<?= $producto['id'] ?>" class="btn btn-primary btn-add-to-cart">
                                        <i class="fas fa-cart-plus me-2"></i>Añadir al Carrito
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-secondary" disabled>
                                        <i class="fas fa-times-circle me-2"></i>Agotado
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <div class="container">
            <p class="mb-0">&copy; <?= date('Y') ?> Tienda Online. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const busquedaInput = document.getElementById('busqueda');
        const categoriaSelect = document.getElementById('categoria');
        const productosContainer = document.getElementById('productos-container');
        const productos = productosContainer.getElementsByClassName('producto-item');

        // Función para filtrar productos por nombre
        function filtrarProductos() {
            const busqueda = busquedaInput.value.toLowerCase();

            Array.from(productos).forEach(producto => {
                const nombre = producto.dataset.nombre;
                const mostrar = nombre.includes(busqueda) || busqueda === '';
                producto.style.display = mostrar ? '' : 'none';
            });
        }

        // Event listeners
        busquedaInput.addEventListener('input', filtrarProductos);
        
        // Cambio de categoría recarga la página con el nuevo filtro
        categoriaSelect.addEventListener('change', function() {
            window.location.href = 'index.php' + (this.value ? '?categoria=' + this.value : '');
        });
    });
    </script>
</body>
</html>

