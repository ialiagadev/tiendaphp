<?php
require_once "../app/models/Producto.php"; // Incluir el modelo

$producto = new Producto();

// Obtener todos los productos
$productos = $producto->getAll();
echo "<h2>Lista de productos</h2>";
echo "<pre>" . print_r($productos, true) . "</pre>";

// Obtener un producto por ID
$productoId = 1;
$productoData = $producto->getById($productoId);
echo "<h2>Detalles del Producto ID $productoId</h2>";
echo "<pre>" . print_r($productoData, true) . "</pre>";

// Obtener productos por categoría
$categoriaId = 1;
$productosCategoria = $producto->getByCategory($categoriaId);
echo "<h2>Productos de la Categoría ID $categoriaId</h2>";
echo "<pre>" . print_r($productosCategoria, true) . "</pre>";
?>
