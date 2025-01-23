<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedido Confirmado</title>
    <link rel="stylesheet" href="/TIENDA/public/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>¡Pedido Confirmado!</h1>
        <?php if (isset($_GET['pedido_id'])): ?>
            <p>Tu pedido ha sido registrado exitosamente con el ID #<?= htmlspecialchars($_GET['pedido_id']) ?></p>
        <?php else: ?>
            <p>No se pudo obtener el ID del pedido. Por favor, contacta con soporte.</p>
        <?php endif; ?>
        <a href="index.php" class="btn btn-primary">Volver a la tienda</a>
    </div>
</body>
</html>
