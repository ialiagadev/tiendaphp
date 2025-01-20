<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Productos</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="text-center">Lista de Productos</h1>
        <ul class="product-list">
            <?php foreach ($productos as $producto): ?>
                <li>
                    <a href="producto.php?id=<?= $producto['id'] ?>">
                        <?= $producto['nombre'] ?> - <strong>$<?= $producto['precio'] ?></strong>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
