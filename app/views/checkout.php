<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Finalizar Compra</title>
    <link rel="stylesheet" href="/TIENDA/public/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Resumen del Pedido</h2>

        <?php if (empty($productos)): ?>
            <p>No hay productos en el carrito.</p>
            <a href="index.php" class="btn btn-primary">Volver a la tienda</a>
        <?php else: ?>
            <ul class="list-group">
                <?php foreach ($productos as $id => $producto): ?>
                    <li class="list-group-item">
                        <?= $producto['nombre'] ?> (<?= $producto['cantidad'] ?>) - 
                        <strong>$<?= number_format($producto['precio'] * $producto['cantidad'], 2) ?></strong>
                    </li>
                <?php endforeach; ?>
            </ul>

            <h3 class="mt-3">Total: <strong>$<?= number_format($total, 2) ?></strong></h3>

            <!-- Formulario para confirmar la compra -->
            <form action="checkout.php" method="POST">
                <button type="submit" class="btn btn-success">Confirmar Compra</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
