<?php
// stripe_checkout.php

// Incluir la configuración de Stripe y los modelos necesarios
require_once "../config/stripe_config.php";
require_once "../app/models/Carrito.php";
require_once "../app/models/Pedido.php";

session_start();

// Verificar que el usuario está autenticado (esto depende de la lógica de tu proyecto)
if (!isset($_SESSION['usuario'])) {
    die("Debes iniciar sesión para proceder con el pago.");
}

$usuario_id = $_SESSION['usuario']['id'];

// Obtener los datos del carrito
$carrito = new Carrito();
$productos = $carrito->obtenerCarrito();
$total = $carrito->obtenerTotal();

if (empty($productos)) {
    die("El carrito está vacío. No se puede procesar el pago.");
}

// Crear el pedido en la base de datos (Estado: "pendiente")
$pedidoModel = new Pedido();
try {
    $pedido_id = $pedidoModel->crearPedido($usuario_id, $total, $productos);
} catch (Exception $e) {
    die("Error al crear el pedido: " . $e->getMessage());
}

// Preparar los items para Stripe Checkout
$line_items = array_map(function ($producto) {
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
}, array_values($productos)); // Asegura índices secuenciales

// Crear la sesión de Stripe Checkout
try {
    $checkout_session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => $line_items,
        'mode' => 'payment',
        // URL de éxito: redirige a success.php pasando el ID del pedido y la sesión de Stripe
'success_url' => 'https://cornflowerblue-alpaca-573297.hostingersite.com/public/pedido_confirmado.php?pedido_id=' . $pedido_id . '&session_id={CHECKOUT_SESSION_ID}',
'cancel_url'  => 'https://cornflowerblue-alpaca-573297.hostingersite.com/carrito.php',

    ]);

    // Redirigir al usuario a la página de pago de Stripe
    header("Location: " . $checkout_session->url);
    exit();
} catch (Exception $e) {
    // Opcional: Si se falla la sesión de Stripe, se podría cancelar el pedido previamente creado.
    // $pedidoModel->cancelarPedido($pedido_id);
    die("❌ Error al crear la sesión de Stripe Checkout: " . $e->getMessage());
}
?>
