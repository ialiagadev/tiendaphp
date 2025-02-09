<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        /* Estilos para que el footer quede abajo */
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }
        .content-wrapper {
            flex: 1;
        }
    </style>
</head>
<body>

<?php include("../app/components/admin_navbar.php"); ?>

    <div class="container content-wrapper mt-4">
        <h1 class="text-center">Panel de Administración</h1>
        <p class="text-center">Bienvenido, <?= htmlspecialchars($_SESSION["usuario"]["nombre"]); ?></p>

        <ul class="list-group">
            <li class="list-group-item"><a href="usuarios.php">Gestión de Usuarios</a></li>
            <li class="list-group-item"><a href="categorias.php">Gestión de Categorías</a></li>
            <li class="list-group-item"><a href="productos.php">Gestión de Productos</a></li>
            <li class="list-group-item"><a href="informes.php">Informes</a></li>
            <li class="list-group-item"><a href="../public/index.php">Volver a la Tienda</a></li>

            <li class="list-group-item"><a href="../public/logout.php" class="text-danger">Cerrar Sesión</a></li>
        </ul>
    </div>

    <?php include("../app/components/footer.php"); ?>

</body>
</html>
