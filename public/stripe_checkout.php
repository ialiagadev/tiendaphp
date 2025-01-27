<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Importar dependencias necesarias
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . "/../app/models/Carrito.php";
require_once __DIR__ . "/../app/controllers/PedidoController.php";

// Cargar configuración de Stripe
$stripeConfig = require __DIR__ . '/../config/stripe_config.php';
\Stripe\Stripe::setApiKey($stripeConfig['secretKey']);

// Inicializar el carrito y obtener productos
$carrito = new Carrito();
$productos = $carrito->obtenerCarrito();
$total = $carrito->obtenerTotal();

// Verificar si hay productos en el carrito
if (empty($productos)) {
    $_SESSION['error'] = "El carrito está vacío. No se puede procesar el pago.";
    header("Location: carrito.php");
    exit();
}

// Calcular la URL base
$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
$baseUrl .= dirname($_SERVER['PHP_SELF']);
$baseUrl = rtrim($baseUrl, '/');

// Crear la sesión de Stripe Checkout
try {
    $line_items = array_map(function ($producto) {
        return [
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => $producto['nombre'],
                    'description' => isset($producto['descripcion']) ? $producto['descripcion'] : null,
                ],
                'unit_amount' => intval($producto['precio'] * 100), // Convertir a céntimos
            ],
            'quantity' => $producto['cantidad'],
        ];
    }, array_values($productos));

    $checkout_session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => $line_items,
        'mode' => 'payment',
        'customer_email' => $_SESSION['usuario']['email'] ?? null,
        'success_url' => $baseUrl . '/pedido_confirmado.php?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => $baseUrl . '/carrito.php',
        'metadata' => [
            'usuario_id' => $_SESSION['usuario']['id'],
            'total_carrito' => $total
        ],
        'payment_intent_data' => [
            'metadata' => [
                'usuario_id' => $_SESSION['usuario']['id'],
                'productos' => json_encode(array_map(function($producto) {
                    return [
                        'id' => $producto['id'],
                        'cantidad' => $producto['cantidad']
                    ];
                }, $productos))
            ]
        ]
    ]);

    // Guardar el ID de la sesión en la sesión de PHP para referencia posterior
    $_SESSION['stripe_checkout_session_id'] = $checkout_session->id;

    // Redireccionar a Stripe
    header("Location: " . $checkout_session->url);
    exit();

} catch (\Stripe\Exception\ApiErrorException $e) {
    // Log del error
    error_log('Error de Stripe: ' . $e->getMessage());
    
    // Guardar mensaje de error en la sesión
    $_SESSION['error'] = "Ha ocurrido un error al procesar el pago. Por favor, inténtalo de nuevo.";
    
    // Redireccionar de vuelta al carrito
    header("Location: carrito.php");
    exit();
} catch (Exception $e) {
    // Log del error general
    error_log('Error general: ' . $e->getMessage());
    
    // Guardar mensaje de error en la sesión
    $_SESSION['error'] = "Ha ocurrido un error inesperado. Por favor, inténtalo de nuevo más tarde.";
    
    // Redireccionar de vuelta al carrito
    header("Location: carrito.php");
    exit();
}
?>