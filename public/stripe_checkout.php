<?php
require_once '../vendor/autoload.php';
require_once "../app/controllers/PedidoController.php";

session_start();

if (!isset($_SESSION['usuario']) || !isset($_SESSION['carrito'])) {
    header("Location: carrito.php");
    exit();
}

$pedidoController = new PedidoController();
$total = $pedidoController->calcularTotalCarrito();

\Stripe\Stripe::setApiKey('tu_clave_secreta_de_stripe');

try {
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $total * 100, // Stripe usa céntimos
                'product_data' => [
                    'name' => 'Compra en Tienda Online',
                ],
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => 'https://cornflowerblue-alpaca-573297.hostingersite.com/confirmar_pedido.php?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => 'https://cornflowerblue-alpaca-573297.hostingersite.com/carrito.php',
    ]);

    echo json_encode(['id' => $session->id]);
} catch(Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

