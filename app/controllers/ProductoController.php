<?php
require_once __DIR__ . "/../models/Producto.php";
require_once __DIR__ . "/../models/Categoria.php";

class ProductoController {
    private $productoModel;
    private $categoriaModel;

    public function __construct() {
        $this->productoModel = new Producto();
        $this->categoriaModel = new Categoria();
    }

    // Obtener todos los productos
    public function obtenerProductos($categoria_id = null) {
        if ($categoria_id) {
            return $this->productoModel->getByCategory($categoria_id);
        }
        return $this->productoModel->getAll();
    }

    // Obtener todas las categorías
    public function obtenerCategorias() {
        return $this->categoriaModel->getAllWithSubcategories();
    }

    // Obtener productos con sus categorías
    public function obtenerProductosConCategorias($categoria_id = null) {
        $productos = $this->obtenerProductos($categoria_id);
        $categorias = $this->obtenerCategorias();

        return [
            'productos' => $productos,
            'categorias' => $categorias
        ];
    }

    // Obtener detalle de un producto
    public function detalle($id) {
        $producto = $this->productoModel->getById($id);
        if (!$producto) {
            echo "Producto no encontrado.";
            return;
        }
        require_once __DIR__ . "/../views/productos/detalle.php";
    }

    // Buscar productos
    public function buscarProductos($termino) {
        return $this->productoModel->buscar($termino);
    }

    // Obtener productos destacados
    public function obtenerProductosDestacados($limite = 4) {
        return $this->productoModel->getDestacados($limite);
    }

    // Obtener productos más vendidos
    public function obtenerProductosMasVendidos($limite = 4) {
        return $this->productoModel->getMasVendidos($limite);
    }

    // Obtener productos recientes
    public function obtenerProductosRecientes($limite = 4) {
        return $this->productoModel->getRecientes($limite);
    }
}

