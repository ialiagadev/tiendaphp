<?php
require_once __DIR__ . "/../app/controllers/PedidoController.php";
require_once __DIR__ . "/../app/controllers/CarritoController.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$pedidoController = new PedidoController();
$carritoController = new CarritoController();

$productos = $carritoController->obtenerCarrito();
$total = $carritoController->obtenerTotal();

$errores = [];
$direccion = [
    'nombre' => '',
    'telefono' => '',
    'direccion' => '',
    'pais' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $direccion = [
        'nombre' => $_POST['nombre'] ?? '',
        'telefono' => $_POST['telefono'] ?? '',
        'direccion' => $_POST['direccion'] ?? '',
        'pais' => $_POST['pais'] ?? ''
    ];

    // Validación simple
    foreach ($direccion as $campo => $valor) {
        if (empty($valor)) {
            $errores[$campo] = "El campo " . ucfirst($campo) . " es obligatorio.";
        }
    }

    if (empty($errores)) {
        // Guardar la dirección en la sesión y redirigir al pago de Stripe
        $_SESSION['direccion_envio'] = $direccion;
        header("Location: stripe_checkout.php");
        exit();
    }
}

// Calcular el costo de envío (puedes ajustar esto según tus necesidades)
$costoEnvio = 0.00;
$totalConEnvio = $total + $costoEnvio;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra - Tienda Online</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include_once __DIR__ . "/../app/components/navbar.php"; ?>

    <div class="container mt-5">
        <h1 class="mb-4">Finalizar Compra</h1>
        
        <?php if (empty($productos)): ?>
            <div class="alert alert-info">
                No hay productos en el carrito. <a href="index.php" class="alert-link">Volver a la tienda</a>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Resumen del Pedido</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group mb-3">
                                <?php foreach ($productos as $producto): ?>
                                    <li class="list-group-item d-flex justify-content-between lh-sm">
                                        <div>
                                            <h6 class="my-0"><?= htmlspecialchars($producto['nombre']) ?></h6>
                                            <small class="text-muted">Cantidad: <?= $producto['cantidad'] ?></small>
                                        </div>
                                        <span class="text-muted">$<?= number_format($producto['precio'] * $producto['cantidad'], 2) ?></span>
                                    </li>
                                <?php endforeach; ?>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Subtotal</span>
                                    <strong>$<?= number_format($total, 2) ?></strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Envío</span>
                                    <strong>$<?= number_format($costoEnvio, 2) ?></strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Total</span>
                                    <strong>$<?= number_format($totalConEnvio, 2) ?></strong>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Dirección de Envío</h5>
                        </div>
                        <div class="card-body">
                            <form action="checkout.php" method="POST">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre completo</label>
                                    <input type="text" class="form-control <?= isset($errores['nombre']) ? 'is-invalid' : '' ?>" id="nombre" name="nombre" value="<?= htmlspecialchars($direccion['nombre'] ?? '') ?>" required>
                                    <?php if (isset($errores['nombre'])): ?>
                                        <div class="invalid-feedback"><?= $errores['nombre'] ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="mb-3">
                                    <label for="telefono" class="form-label">Número de teléfono</label>
                                    <input type="tel" class="form-control <?= isset($errores['telefono']) ? 'is-invalid' : '' ?>" id="telefono" name="telefono" value="<?= htmlspecialchars($direccion['telefono'] ?? '') ?>" required>
                                    <?php if (isset($errores['telefono'])): ?>
                                        <div class="invalid-feedback"><?= $errores['telefono'] ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="mb-3">
                                    <label for="direccion" class="form-label">Dirección completa</label>
                                    <input type="text" class="form-control <?= isset($errores['direccion']) ? 'is-invalid' : '' ?>" id="direccion" name="direccion" value="<?= htmlspecialchars($direccion['direccion'] ?? '') ?>" placeholder="Calle, número, ciudad, estado, código postal" required>
                                    <?php if (isset($errores['direccion'])): ?>
                                        <div class="invalid-feedback"><?= $errores['direccion'] ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="mb-3">
                                    <label for="pais" class="form-label">País</label>
                                    <input type="text" class="form-control <?= isset($errores['pais']) ? 'is-invalid' : '' ?>" id="pais" name="pais" value="<?= htmlspecialchars($direccion['pais'] ?? '') ?>" required>
                                    <?php if (isset($errores['pais'])): ?>
                                        <div class="invalid-feedback"><?= $errores['pais'] ?></div>
                                    <?php endif; ?>
                                </div>
                                <button type="submit" class="btn btn-primary">Continuar al Pago</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Pago con Stripe</h5>
                            <p class="card-text">Una vez que hayas ingresado tu dirección de envío, serás redirigido a Stripe para completar tu pago de forma segura.</p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php include_once __DIR__ . "/../app/components/footer.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

