<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Carrito de Compras</h1>
        <?php if (empty($productos)): ?>
            <p>No hay productos en el carrito.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $id => $producto): ?>
                        <tr>
                            <td><?= $producto['nombre'] ?></td>
                            <td>$<?= number_format($producto['precio'], 2) ?></td>
                            <td><?= $producto['cantidad'] ?></td>
                            <td>$<?= number_format($producto['precio'] * $producto['cantidad'], 2) ?></td>
                            <td>
                                <a href="carrito.php?accion=eliminar&id=<?= $id ?>" class="btn btn-danger">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <h3>Total: $<?= number_format($total, 2) ?></h3>
            <a href="carrito.php?accion=vaciar" class="btn btn-warning">Vaciar Carrito</a>
        <?php endif; ?>
        <a href="producto.php" class="btn btn-primary">Seguir Comprando</a>
    </div>
</body>
</html>
