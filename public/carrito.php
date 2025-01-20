<?php
require_once "../app/controllers/CarritoController.php";

$controller = new CarritoController();

$accion = $_GET['accion'] ?? 'index';
$id = $_GET['id'] ?? null;

if ($accion == 'agregar' && $id) {
    $controller->agregar($id);
} elseif ($accion == 'eliminar' && $id) {
    $controller->eliminar($id);
} elseif ($accion == 'vaciar') {
    $controller->vaciar();
} else {
    $controller->index();
}
?>
