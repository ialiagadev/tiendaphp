<?php
session_start(); // Iniciar sesión para almacenar el carrito

class Carrito {
    public function __construct() {
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = []; // Inicializar el carrito en la sesión si no existe
        }
    }

    // Agregar un producto al carrito
    public function agregarProducto($id, $nombre, $precio, $cantidad = 1) {
        if (isset($_SESSION['carrito'][$id])) {
            $_SESSION['carrito'][$id]['cantidad'] += $cantidad;
        } else {
            $_SESSION['carrito'][$id] = [
                'nombre' => $nombre,
                'precio' => $precio,
                'cantidad' => $cantidad
            ];
        }
    }

    // Obtener todos los productos en el carrito
    public function obtenerCarrito() {
        return $_SESSION['carrito'];
    }

    // Eliminar un producto del carrito
    public function eliminarProducto($id) {
        if (isset($_SESSION['carrito'][$id])) {
            unset($_SESSION['carrito'][$id]);
        }
    }

    // Vaciar el carrito completamente
    public function vaciarCarrito() {
        $_SESSION['carrito'] = [];
    }

    // Calcular el total del carrito
    public function obtenerTotal() {
        $total = 0;
        foreach ($_SESSION['carrito'] as $producto) {
            $total += $producto['precio'] * $producto['cantidad'];
        }
        return $total;
    }
}
?>
