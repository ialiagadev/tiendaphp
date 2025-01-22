<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Pedidos</title>
    <link rel="stylesheet" href="/TIENDA/public/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center">Mis Pedidos</h1>

        <?php if (!isset($pedidos) || empty($pedidos)): ?>
            <p class="alert alert-warning">No tienes pedidos aún.</p>
        <?php else: ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr>
                            <td>#<?= htmlspecialchars($pedido['id']) ?></td>
                            <td>$<?= number_format($pedido['total'], 2) ?></td>
                            <td><?= htmlspecialchars($pedido['estado']) ?></td>
                            <td><?= htmlspecialchars($pedido['fecha']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <a href="index.php" class="btn btn-primary">Volver a la tienda</a>
    </div>
</body>
</html>
