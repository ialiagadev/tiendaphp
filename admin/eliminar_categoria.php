<?php
session_start();
require_once "../app/controllers/CategoriaController.php";

// Verificar si el usuario tiene permisos de administrador
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// Verificar si se proporciona un ID válido
if (!isset($_GET["id"]) || empty($_GET["id"])) {
    header("Location: categorias.php");
    exit;
}

$categoriaController = new CategoriaController();
$id = $_GET["id"];

// Intentar eliminar la categoría
$resultado = $categoriaController->eliminarCategoria($id);

// Redirigir con mensaje de éxito o error
if ($resultado) {
    $_SESSION['success'] = "✅ Categoría eliminada correctamente.";
} else {
    $_SESSION['error'] = "❌ No se pudo eliminar la categoría.";
}
header("Location: categorias.php");
exit;
