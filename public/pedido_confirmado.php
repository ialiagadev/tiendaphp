<?php
require_once "../config/stripe_config.php";
require_once "../app/models/Pedido.php";

\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

// 1️⃣ Obtener el session_id de Stripe desde la URL
$session_id = $_GET['session_id'] ?? null;
if (!$session_id) {
    die("Error: No se encontró el identificador de la sesión de pago.");
}

// 2️⃣ Recuperar la sesión de pago desde Stripe
try {
    $session = \Stripe\Checkout\Session::retrieve($session_id);
} catch (Exception $e) {
    die("Error al recuperar la sesión de Stripe: " . $e->getMessage());
}

// 3️⃣ (Opcional) Obtener información del cliente
$cliente_email = $session->customer_details->email;

// 4️⃣ Recuperar el pedido relacionado en la base de datos
$pedidoModel = new Pedido();
$pedido = $pedidoModel->obtenerPedidoPorSessionId($session_id);

if (!$pedido) {
    die("Error: No se encontró un pedido asociado a esta sesión de pago.");
}

$pedido_id = $pedido['id']; // ID real del pedido en la base de datos
?>

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
        <p class="text-center">Tu pedido ha sido registrado exitosamente con el ID <strong>#<?= htmlspecialchars($pedido_id) ?></strong>.</p>
        <p class="text-center">Hemos enviado una confirmación a <strong><?= htmlspecialchars($cliente_email) ?></strong>.</p>
        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-primary">Volver a la Tienda</a>
        </div>
    </div>
</body>
</html>
