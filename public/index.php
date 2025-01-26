<?php
require_once "../app/controllers/ProductoController.php";
session_start();

$controller = new ProductoController();

$categoria_id = isset($_GET['categoria']) ? intval($_GET['categoria']) : null;
$precio_min = isset($_GET['precio_min']) ? floatval($_GET['precio_min']) : null;
$precio_max = isset($_GET['precio_max']) ? floatval($_GET['precio_max']) : null;
$pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$por_pagina = 12; // Número de productos por página

$data = $controller->obtenerProductosConCategorias($categoria_id, $precio_min, $precio_max, $pagina, $por_pagina);
$productos = $data['productos'];
$categorias = $data['categorias'];
$total_productos = $data['total_productos'];
$total_paginas = ceil($total_productos / $por_pagina);
$rango_precios = $data['rango_precios'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Online</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.6.3/nouislider.min.css">
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
            height: 100%;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .card-img-wrapper {
            position: relative;
            padding-top: 75%; /* 4:3 Aspect Ratio */
            overflow: hidden;
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        .card-body {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        .card-text {
            flex-grow: 1;
        }
        .product-price {
            font-size: 1.25rem;
            font-weight: bold;
            color: #007bff;
        }
        .stock-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1;
        }
        #categoria {
            max-height: 300px;
            overflow-y: auto;
        }
        #categoria optgroup {
            font-weight: bold;
        }
        #categoria option {
            padding-left: 20px;
        }
        #precio-slider {
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .btn-add-to-cart {
            transition: background-color 0.3s ease-in-out;
        }
        .btn-add-to-cart:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <?php include_once __DIR__ . "/../app/components/navbar.php"; ?>

    <main class="container my-4">
        <h1 class="text-center mb-4">Nuestros Productos</h1>
        
        <div class="row">
            <div class="col-md-3">
                <h4>Filtros</h4>
                <form id="filtro-form" action="index.php" method="GET">
                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoría</label>
                        <select id="categoria" name="categoria" class="form-select">
                            <option value="">Todas las categorías</option>
                            <?php foreach ($categorias as $categoria): ?>
                                <optgroup label="<?= htmlspecialchars($categoria['nombre']) ?>">
                                    <?php if (!empty($categoria['subcategorias'])): ?>
                                        <?php foreach ($categoria['subcategorias'] as $subcategoria): ?>
                                            <option value="<?= $subcategoria['id'] ?>" <?= $categoria_id == $subcategoria['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($subcategoria['nombre']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="<?= $categoria['id'] ?>" <?= $categoria_id == $categoria['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($categoria['nombre']) ?>
                                        </option>
                                    <?php endif; ?>
                                </optgroup>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="precio-slider" class="form-label">Rango de Precio</label>
                        <div id="precio-slider"></div>
                        <div class="d-flex justify-content-between mt-2">
                            <span id="precio-min-label"></span>
                            <span id="precio-max-label"></span>
                        </div>
                        <input type="hidden" id="precio_min" name="precio_min" value="<?= $precio_min ?? $rango_precios['min_precio'] ?>">
                        <input type="hidden" id="precio_max" name="precio_max" value="<?= $precio_max ?? $rango_precios['max_precio'] ?>">
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
                        <a href="index.php" class="btn btn-secondary">Restablecer Filtros</a>
                    </div>
                </form>
            </div>
            <div class="col-md-9">
                <div class="mb-3">
                    <input type="text" id="busqueda" class="form-control" placeholder="Buscar productos...">
                </div>
                <?php if ($productos): ?>
                    <div id="productos-container" class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
                        <?php foreach ($productos as $producto): ?>
                            <div class="col producto-item" data-nombre="<?= strtolower(htmlspecialchars($producto['nombre'])) ?>">
                                <div class="card h-100">
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
                    <!-- Paginación -->
                    <nav aria-label="Page navigation" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                                <li class="page-item <?= $i == $pagina ? 'active' : '' ?>">
                                    <a class="page-link" href="?pagina=<?= $i ?><?= $categoria_id ? '&categoria=' . $categoria_id : '' ?><?= $precio_min ? '&precio_min=' . $precio_min : '' ?><?= $precio_max ? '&precio_max=' . $precio_max : '' ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php else: ?>
                    <div class="text-center">
                        <div class="alert alert-info" role="alert">
                            No hay productos disponibles en este momento.
                        </div>
                        <a href="index.php" class="btn btn-primary mt-3">
                            <i class="fas fa-sync-alt me-2"></i>Restablecer Filtros
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <?php include_once __DIR__ . "/../app/components/footer.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.6.3/nouislider.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const busquedaInput = document.getElementById('busqueda');
        const productosContainer = document.getElementById('productos-container');
        const productos = productosContainer.getElementsByClassName('producto-item');
        const slider = document.getElementById('precio-slider');
        const precioMinInput = document.getElementById('precio_min');
        const precioMaxInput = document.getElementById('precio_max');
        const precioMinLabel = document.getElementById('precio-min-label');
        const precioMaxLabel = document.getElementById('precio-max-label');

        // Función para filtrar productos por nombre
        function filtrarProductos() {
            const busqueda = busquedaInput.value.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");

            Array.from(productos).forEach(producto => {
                const nombre = producto.dataset.nombre.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                const mostrar = nombre.includes(busqueda);
                producto.style.display = mostrar ? '' : 'none';
            });
        }

        // Event listeners
        busquedaInput.addEventListener('input', filtrarProductos);
        
        // Inicializar el slider de precios
        const minPrecio = <?= $rango_precios['min_precio'] ?>;
        const maxPrecio = <?= $rango_precios['max_precio'] ?>;

        noUiSlider.create(slider, {
            start: [<?= $precio_min ?? $rango_precios['min_precio'] ?>, <?= $precio_max ?? $rango_precios['max_precio'] ?>],
            connect: true,
            range: {
                'min': minPrecio,
                'max': maxPrecio
            },
            step: 1
        });

        slider.noUiSlider.on('update', function (values, handle) {
            const value = Math.round(values[handle]);
            if (handle) {
                precioMaxInput.value = value;
                precioMaxLabel.innerHTML = '$' + value;
            } else {
                precioMinInput.value = value;
                precioMinLabel.innerHTML = '$' + value;
            }
        });

        // Cambio de categoría recarga la página con el nuevo filtro
        document.getElementById('categoria').addEventListener('change', function() {
            document.getElementById('filtro-form').submit();
        });
    });
    </script>
</body>
</html>

