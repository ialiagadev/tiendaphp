<?php
require_once __DIR__ . "/../models/Producto.php"; // Incluir el modelo

class ProductoController {
    private $productoModel;

    public function __construct() {
        $this->productoModel = new Producto(); // Instancia del modelo
    }

    // Mostrar todos los productos
    public function index() {
        $productos = $this->productoModel->getAll();
        require_once __DIR__ . "/../views/productos/index.php"; // Cargar la vista
    }

    // Mostrar detalle de un producto
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
