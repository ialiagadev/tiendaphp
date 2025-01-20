<?php
require_once "../app/controllers/ProductoController.php";

$controller = new ProductoController();

if (isset($_GET['id'])) {
    $controller->detalle($_GET['id']);
} else {
    $controller->index();
}
?>
