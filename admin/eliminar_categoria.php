<?php
session_start();
require_once "../app/controllers/CategoriaController.php";

// Verificar si el usuario tiene permisos de administrador
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$categoriaController = new CategoriaController();

// Asegurar que el método de solicitud sea POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $_SESSION["error"] = "❌ Acción no permitida.";
    header("Location: categorias.php");
    exit;
}

// Validar el ID de la categoría
if (!isset($_POST["id"]) || !is_numeric($_POST["id"])) {
    $_SESSION["error"] = "❌ ID de categoría no válido.";
    header("Location: categorias.php");
    exit;
}

$id = intval($_POST["id"]);

// Intentar eliminar la categoría
if ($categoriaController->eliminarCategoria($id)) {
    $_SESSION['success'] = "✅ Categoría eliminada correctamente.";
} else {
    $_SESSION['error'] = "❌ No se pudo eliminar la categoría.";
}

header("Location: categorias.php");
exit;
