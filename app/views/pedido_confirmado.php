<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedido Confirmado</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <h2>¡Gracias por tu compra!</h2>
        <p>Tu pedido ha sido confirmado con el número <strong>#<?= $_GET['pedido_id'] ?></strong>.</p>
        <a href="index.php">Volver a la tienda</a>
    </div>
</body>
</html>
