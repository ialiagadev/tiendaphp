<?php
require_once __DIR__ . "/../models/Carrito.php";
require_once __DIR__ . "/../models/Producto.php";

class CarritoController {
    private $carrito;
    private $productoModel;

    public function __construct() {
        $this->carrito = new Carrito();
        $this->productoModel = new Producto();
    }

    // Mostrar el carrito
    public function index() {
        $productos = $this->obtenerCarrito();
        $total = $this->obtenerTotal();
        require_once __DIR__ . "/../views/carrito.php";
    }

    // Obtener el contenido del carrito
    public function obtenerCarrito() {
        return $this->carrito->obtenerCarrito();
    }

    // Obtener el total del carrito
    public function obtenerTotal() {
        return $this->carrito->obtenerTotal();
    }

    // Agregar un producto al carrito
    public function agregar($id) {
        $producto = $this->productoModel->getById($id);
        if ($producto) {
            $this->carrito->agregarProducto($id, $producto['nombre'], $producto['precio']);
        }
        header("Location: carrito.php");
    }

    // Eliminar un producto del carrito
    public function eliminar($id) {
        $this->carrito->eliminarProducto($id);
        header("Location: carrito.php");
    }

    // Vaciar el carrito
    public function vaciar() {
        $this->carrito->vaciarCarrito();
        header("Location: carrito.php");
    }
}
?>

