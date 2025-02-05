<?php
session_start();
require_once "../app/controllers/UsuarioController.php";

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$usuarioController = new UsuarioController();

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["id"])) {
    $id = $_GET["id"];

    // Verificamos que el usuario existe
    $usuario = $usuarioController->obtenerUsuarioPorId($id);
    if (!$usuario) {
        header("Location: usuarios.php?error=Usuario no encontrado.");
        exit();
    }

    // Si el usuario es admin, prevenir eliminación
    if ($usuario["rol"] === "admin") {
        header("Location: usuarios.php?error=No puedes eliminar a un administrador.");
        exit();
    }

    // Intentamos eliminar el usuario (baja lógica)
    if ($usuarioController->eliminarUsuario($id)) {
        header("Location: usuarios.php?success=Usuario eliminado correctamente.");
    } else {
        header("Location: usuarios.php?error=No se pudo eliminar el usuario.");
    }
    exit();
} else {
    header("Location: usuarios.php");
    exit();
}
?>
