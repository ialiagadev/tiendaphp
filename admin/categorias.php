<?php
session_start();
require_once "../app/controllers/CategoriaController.php";

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$controller = new CategoriaController();
$categorias = $controller->obtenerCategorias(); // Obtener todas las categorías

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Categorías</title>
    <link rel="stylesheet" href="css/admin-styles.css">
</head>
<body>
    <?php include "templates/header.php"; ?>
    <div class="container">
        <h1 class="text-center my-4">Gestión de Categorías</h1>
        <a href="nueva_categoria.php" class="btn btn-primary mb-3">Nueva Categoría</a>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categorias as $categoria): ?>
                    <tr>
                        <td><?= $categoria['id'] ?></td>
                        <td><?= $categoria['nombre'] ?></td>
                        <td>
                            <a href="editar_categoria.php?id=<?= $categoria['id'] ?>" class="btn btn-warning">Editar</a>
                            <a href="eliminar_categoria.php?id=<?= $categoria['id'] ?>" class="btn btn-danger">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php include "templates/footer.php"; ?>
</body>
</html>
