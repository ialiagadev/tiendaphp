<?php
session_start();
require_once "../app/controllers/ProductoController.php";

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$productoController = new ProductoController();

// Verificar si el ID del producto está presente en la URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: productos.php");
    exit;
}

$producto_id = $_GET['id'];

// Llamar al método para desactivar el producto (baja lógica)
$productoController->eliminarProducto($producto_id);

// Redirigir a la lista de productos
header("Location: productos.php");
exit;
?>
