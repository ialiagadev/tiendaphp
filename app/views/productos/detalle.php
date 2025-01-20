<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $producto['nombre'] ?></title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1><?= $producto['nombre'] ?></h1>
        <p><?= $producto['descripcion'] ?></p>
        <p><strong>Precio:</strong> $<?= $producto['precio'] ?></p>
        <p><strong>Stock:</strong> <?= $producto['stock'] ?></p>
        <a href="../public/carrito.php?accion=agregar&id=<?= $producto['id'] ?>" class="btn btn-success">Añadir al Carrito</a>
        <a href="index.php" class="button">Volver</a>
    </div>
</body>
</html>
