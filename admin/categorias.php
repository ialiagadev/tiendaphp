<?php
session_start();
require_once "../app/controllers/CategoriaController.php";

// Verificar que el usuario tiene rol de administrador
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$controller = new CategoriaController();
$categorias = $controller->obtenerCategorias(); // Obtener todas las categorías con jerarquía

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Categorías</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .subcategoria {
            padding-left: 20px;
            font-style: italic;
        }
    </style>
</head>
<body>
<?php include("../app/components/admin_navbar.php"); ?>

    <div class="container">
        <h1 class="text-center my-4">Gestión de Categorías</h1>

        <!-- Mensajes de éxito o error -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Botón para añadir una nueva categoría -->
        <a href="nueva_categoria.php" class="btn btn-primary mb-3">Nueva Categoría</a>

        <!-- Tabla de categorías -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Categoría Padre</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categorias as $categoria): ?>
                    <tr>
                        <td><?= $categoria['id'] ?></td>
                        <td><?= htmlspecialchars($categoria['nombre']) ?></td>
                        <td><?= htmlspecialchars($categoria['descripcion']) ?></td>
                        <td><?= $categoria['padre_id'] ? htmlspecialchars($categoria['categoria_padre']) : '<span class="text-secondary">N/A</span>' ?></td>
                        <td><?= isset($categoria['activo']) && $categoria['activo'] ? "✅ Activa" : "❌ Inactiva" ?></td>
                        <td>
                            <a href="editar_categoria.php?id=<?= $categoria['id'] ?>" class="btn btn-warning btn-sm">Editar</a>

                            <?php if ($categoria['activo']): ?>
                                <form action="eliminar_categoria.php" method="POST" class="d-inline">
                                    <input type="hidden" name="id" value="<?= $categoria['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que quieres eliminar esta categoría?')">Eliminar</button>
                                </form>
                            <?php else: ?>
                                <form action="reactivar_categoria.php" method="POST" class="d-inline">
                                    <input type="hidden" name="id" value="<?= $categoria['id'] ?>">
                                    <button type="submit" class="btn btn-success btn-sm">Reactivar</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>

                    <!-- Subcategorías -->
                    <?php if (!empty($categoria['subcategorias'])): ?>
                        <?php foreach ($categoria['subcategorias'] as $subcategoria): ?>
                            <tr class="subcategoria">
                                <td><?= $subcategoria['id'] ?></td>
                                <td>↳ <?= htmlspecialchars($subcategoria['nombre']) ?></td>
                                <td><?= htmlspecialchars($subcategoria['descripcion']) ?></td>
                                <td><?= htmlspecialchars($categoria['nombre']) ?></td>
                                <td><?= isset($subcategoria['activo']) && $subcategoria['activo'] ? "✅ Activa" : "❌ Inactiva" ?></td>
                                <td>
                                    <a href="editar_categoria.php?id=<?= $subcategoria['id'] ?>" class="btn btn-warning btn-sm">Editar</a>

                                    <?php if ($subcategoria['activo']): ?>
                                        <form action="eliminar_categoria.php" method="POST" class="d-inline">
                                            <input type="hidden" name="id" value="<?= $subcategoria['id'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que quieres eliminar esta subcategoría?')">Eliminar</button>
                                        </form>
                                    <?php else: ?>
                                        <form action="reactivar_categoria.php" method="POST" class="d-inline">
                                            <input type="hidden" name="id" value="<?= $subcategoria['id'] ?>">
                                            <button type="submit" class="btn btn-success btn-sm">Reactivar</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php include("../app/components/footer.php"); ?>
</body>
</html>
