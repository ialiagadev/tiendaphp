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
    // Obtener detalles de un producto
    public function detalle($id) {
        if (!isset($id) || empty($id)) {
            header("Location: index.php");
            exit();
        }

        $producto = $this->productoModel->getById($id);

        if (!$producto) {
            $_SESSION['error'] = "El producto no existe.";
            header("Location: index.php");
            exit();
        }

        include __DIR__ . "/../views/detalle.php";
    }



    // Agregar un nuevo producto con validaciones
    public function agregarProducto($datos) {
        if (empty($datos['nombre']) || empty($datos['precio']) || empty($datos['categoria_id'])) {
            return ['error' => 'Faltan datos obligatorios'];
        }

        $id = $this->productoModel->crear($datos);
        return $id ? ['success' => true, 'id' => $id] : ['error' => 'No se pudo crear el producto'];
    }

    // Búsqueda de productos por nombre o categoría (Global)
    public function buscarProductos($termino) {
        return $this->productoModel->getFiltered(null, null, null, 0, 1000, $termino);
    }

    // Obtener productos destacados
    public function obtenerProductosDestacados($limite = 4) {
        return $this->productoModel->getDestacados($limite);
    }

    // Obtener los productos más vendidos
    public function obtenerProductosMasVendidos($limite = 4) {
        return $this->productoModel->getMasVendidos($limite);
    }

    // Obtener los productos recientes
    public function obtenerProductosRecientes($limite = 4) {
        return $this->productoModel->getRecientes($limite);
    }

    // Actualizar un producto existente con validaciones
    public function actualizarProducto($id, $datos) {
        if (empty($datos['nombre']) || empty($datos['precio']) || empty($datos['categoria_id'])) {
            return ['error' => 'Faltan datos obligatorios'];
        }

        $actualizado = $this->productoModel->actualizar($id, $datos);
        return $actualizado ? ['success' => true] : ['error' => 'No se pudo actualizar el producto'];
    }
  
    
    // Eliminar un producto (baja lógica)
    public function eliminarProducto($id) {
        $eliminado = $this->productoModel->eliminar($id);
        return $eliminado ? ['success' => true] : ['error' => 'No se pudo eliminar el producto'];
    }

    // Reactivar un producto eliminado
    public function reactivarProducto($id) {
        $reactivado = $this->productoModel->reactivar($id);
        return $reactivado ? ['success' => true] : ['error' => 'No se pudo reactivar el producto'];
    }

    // Obtener productos con filtros, paginación y búsqueda global
    public function obtenerProductosConCategorias($categoria_id = null, $precio_min = null, $precio_max = null, $pagina = 1, $por_pagina = 12, $busqueda = null) {
        $offset = ($pagina - 1) * $por_pagina;
        
        $productos = $this->productoModel->getFiltered($categoria_id, $precio_min, $precio_max, $offset, $por_pagina, $busqueda);
        $total_productos = $this->productoModel->getTotalFiltered($categoria_id, $precio_min, $precio_max, $busqueda);
        $categorias = $this->categoriaModel->getAllWithSubcategories();
        $rango_precios = $this->productoModel->getRangoPreciosGlobal();

        return [
            'productos' => $productos,
            'categorias' => $categorias,
            'total_productos' => $total_productos,
            'rango_precios' => $rango_precios
        ];
    }
}
