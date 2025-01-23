<?php
require_once "../config/stripe_config.php";
require_once "../app/models/Carrito.php";
session_start();

// Obtener los datos del carrito
$carrito = new Carrito();
$productos = $carrito->obtenerCarrito();
$total = $carrito->obtenerTotal();

if (empty($productos)) {
    die("El carrito está vacío. No se puede procesar el pago.");
}

// Crear una sesión de Stripe Checkout
try {
    $checkout_session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
       'line_items' => array_map(function ($producto) {
    return [
        'price_data' => [
            'currency' => 'usd',
            'product_data' => [
                'name' => $producto['nombre'],
            ],
            'unit_amount' => $producto['precio'] * 100, // Stripe trabaja en centavos
        ],
        'quantity' => $producto['cantidad'],
    ];
}, array_values($productos)), // Convertir a un array con índices secuenciales

        'mode' => 'payment',
        'success_url' => 'http://localhost/TIENDA/public/pedido_confirmado.php?pedido_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => 'http://localhost/TIENDA/public/carrito.php',
    ]);

    header("Location: " . $checkout_session->url);
    exit();
} catch (Exception $e) {
    echo "❌ Error al crear la sesión de Stripe Checkout: " . $e->getMessage();
    exit();
}
?>
