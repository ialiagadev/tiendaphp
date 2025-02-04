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

// Reactivar el producto
$productoController->reactivarProducto($producto_id);

// Redirigir a la lista de productos
header("Location: productos.php");
exit;
?>
/* 

📌 Explicación del Código
Verifica si el usuario es administrador

Si no lo es, lo redirige a index.php.
Obtiene el ID del producto

Se asegura de que $_GET['id'] esté presente y tenga un valor válido.
Llama a reactivarProducto($id) en el controlador

Esto cambia activo = 1 en la base de datos.
Redirige a la lista de productos

Una vez reactivado, redirige automáticamente a productos.php.

*/