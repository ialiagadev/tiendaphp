<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedido Confirmado</title>
    <link rel="stylesheet" href="/TIENDA/public/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center text-success">¡Pedido Confirmado!</h1>
        <p class="text-center">Tu pedido ha sido registrado exitosamente con el ID <strong>#<?= htmlspecialchars($_GET['pedido_id']) ?></strong>.</p>
        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-primary">Volver a la Tienda</a>
        </div>
    </div>
</body>
</html>
