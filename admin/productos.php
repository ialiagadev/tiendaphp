<?php
session_start();
require_once "../app/controllers/ProductoController.php";

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$controller = new ProductoController();

// Obtener término de búsqueda si existe
$busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : null;

// Configuración de paginación
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$productos_por_pagina = 10;

$productosData = $controller->obtenerProductosConCategorias(null, null, null, $pagina_actual, $productos_por_pagina, $busqueda);
$productos = $productosData['productos'];
$categorias = $productosData['categorias'];
$total_productos = $productosData['total_productos'];

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Productos</title>
    <link rel="stylesheet" href="css/admin-styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }
        .container {
            flex: 1;
        }
        footer {
            background-color: #f8f9fa;
            padding: 10px;
            text-align: center;
            margin-top: auto;
            width: 100%;
        }
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="d-flex flex-column min-vh-100">
<?php include("../app/components/admin_navbar.php"); ?>
    
    <div class="container">
        <h1 class="text-center my-4">Gestión de Productos</h1>

        <!-- Botón para añadir un nuevo producto -->
        <a href="nuevo_producto.php" class="btn btn-primary mb-3">Nuevo Producto</a>

        <!-- Formulario de búsqueda -->
        <form method="GET" action="productos.php" class="mb-3 d-flex">
            <div class="input-group">
                <input type="text" name="busqueda" class="form-control" placeholder="Buscar productos..." value="<?= htmlspecialchars($busqueda) ?>">
                <button type="submit" class="btn btn-outline-primary">🔍 Buscar</button>
            </div>
            <?php if (!empty($busqueda)): ?>
                <a href="productos.php" class="btn btn-outline-secondary ms-2">🔄 Mostrar Todos</a>
            <?php endif; ?>
        </form>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Categoría</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($productos)): ?>
                    <tr><td colspan="8" class="text-center">❌ No se encontraron productos.</td></tr>
                <?php else: ?>
                    <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td><?= $producto['id'] ?></td>
                            <td>
                                <img src="../public/<?= htmlspecialchars($producto['imagen']) ?>" class="product-image" alt="<?= htmlspecialchars($producto['nombre']) ?>">
                            </td>
                            <td><?= htmlspecialchars($producto['nombre']) ?></td>
                            <td>$<?= number_format($producto['precio'], 2) ?></td>
                            <td><?= $producto['stock'] ?></td>
                            <td><?= htmlspecialchars($producto['categoria_nombre']) ?></td>
                            <td><?= $producto['activo'] ? "✅ Activo" : "❌ Inactivo" ?></td>
                            <td>
                                <a href="editar_producto.php?id=<?= $producto['id'] ?>" class="btn btn-warning btn-sm">✏️ Editar</a>
                                <a href="eliminar_producto.php?id=<?= $producto['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que quieres eliminar este producto?')">🗑️ Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Paginación -->
        <nav>
            <ul class="pagination justify-content-center">
                <?php
                $total_paginas = ceil($total_productos / $productos_por_pagina);
                for ($i = 1; $i <= $total_paginas; $i++):
                ?>
                    <li class="page-item <?= ($i === $pagina_actual) ? 'active' : '' ?>">
                        <a class="page-link" href="productos.php?pagina=<?= $i ?>&busqueda=<?= urlencode($busqueda) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>

    <?php include("../app/components/footer.php"); ?>
</div>
</body>
</html>
