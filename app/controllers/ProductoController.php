<?php
require_once __DIR__ . "/../models/Producto.php"; // Incluir el modelo

class ProductoController {
    private $productoModel;

    public function __construct() {
        $this->productoModel = new Producto(); // Instancia del modelo
    }

    // Obtener todos los productos
    public function obtenerProductos() {
        return $this->productoModel->getAll();
    }

    // Obtener detalle de un producto
    public function detalle($id) {
        $producto = $this->productoModel->getById($id);
        if (!$producto) {
            echo "Producto no encontrado.";
            return;
        }
        require_once __DIR__ . "/../views/productos/detalle.php"; // Cargar la vista
    }
}
?>
