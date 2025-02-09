<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../app/controllers/ProductoController.php";

$controller = new ProductoController();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("<div class='container mt-5 alert alert-danger'>Error: No se ha recibido el ID del producto.</div>");
}

$producto = $controller->obtenerProducto($_GET['id']);

if (!$producto) {
    die("<div class='container mt-5 alert alert-warning'>Error: Producto no encontrado en la base de datos.</div>");
}

// Comprobar si los datos del producto están bien cargados
echo "<pre>";
var_dump($producto);
echo "</pre>";
exit();
?>
