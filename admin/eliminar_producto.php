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
/*  Explicación del Código
Verifica si el usuario es administrador

Si no es admin, se redirige a index.php.
Obtiene el ID del producto

Se verifica que $_GET['id'] esté presente.
Si no hay un ID válido, redirige a productos.php.
Llama al método eliminarProducto() del controlador

Este método hace una baja lógica (UPDATE productos SET activo = 0 en la base de datos).
Redirige a la lista de productos

Una vez eliminado el producto, redirige automáticamente a productos.php.*/
