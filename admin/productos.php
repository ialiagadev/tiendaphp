<?php
session_start();
require_once "../app/controllers/ProductoController.php";

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$controller = new ProductoController();
$productos = $controller->obtenerProductos(); // Obtener todos los productos

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Productos</title>
    <link rel="stylesheet" href="css/admin-styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <?php include "templates/header.php"; ?>
    
    <div class="container">
        <h1 class="text-center my-4">Gestión de Productos</h1>

        <!-- Botón para añadir un nuevo producto -->
        <a href="nuevo_producto.php" class="btn btn-primary mb-3">Nuevo Producto</a>

        <!-- Barra de búsqueda -->
        <input type="text" id="searchBar" class="form-control mb-3" placeholder="Buscar productos...">

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Categoría</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="productList">
                <?php foreach ($productos as $producto): ?>
                    <tr data-name="<?= htmlspecialchars($producto['nombre']) ?>" data-category="<?= htmlspecialchars($producto['categoria_nombre']) ?>">
                        <td><?= $producto['id'] ?></td>
                        <td><?= htmlspecialchars($producto['nombre']) ?></td>
                        <td>$<?= number_format($producto['precio'], 2) ?></td>
                        <td><?= $producto['stock'] ?></td>
                        <td><?= htmlspecialchars($producto['categoria_nombre']) ?></td>
                        <td><?= $producto['activo'] ? "Activo" : "Inactivo" ?></td>
                        <td>
                            <a href="editar_producto.php?id=<?= $producto['id'] ?>" class="btn btn-warning">Editar</a>
                            <a href="eliminar_producto.php?id=<?= $producto['id'] ?>" class="btn btn-danger">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php include "templates/footer.php"; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchBar = document.getElementById('searchBar');
            const productList = document.getElementById('productList').getElementsByTagName('tr');

            searchBar.addEventListener('input', function() {
                const searchTerm = searchBar.value.toLowerCase();
                for (let product of productList) {
                    const productName = product.getAttribute('data-name').toLowerCase();
                    const productCategory = product.getAttribute('data-category').toLowerCase();
                    
                    if (productName.includes(searchTerm) || productCategory.includes(searchTerm)) {
                        product.style.display = '';
                    } else {
                        product.style.display = 'none';
                    }
                }
            });
        });
    </script>
</body>
</html>
