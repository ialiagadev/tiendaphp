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

    public function obtenerProductosConCategorias($categoria_id = null, $precio_min = null, $precio_max = null, $pagina = 1, $por_pagina = 12) {
        $offset = ($pagina - 1) * $por_pagina;
        
        $productos = $this->productoModel->getFiltered($categoria_id, $precio_min, $precio_max, $offset, $por_pagina);
        $total_productos = $this->productoModel->getTotalFiltered($categoria_id, $precio_min, $precio_max);
        $categorias = $this->categoriaModel->getAllWithSubcategories();
        $rango_precios = $this->productoModel->getRangoPreciosGlobal();

        return [
            'productos' => $productos,
            'categorias' => $categorias,
            'total_productos' => $total_productos,
            'rango_precios' => $rango_precios
        ];
    }

    public function detalle($id) {
        $producto = $this->productoModel->getById($id);
        if (!$producto) {
            echo "Producto no encontrado.";
            return;
        }
        require_once __DIR__ . "/../views/productos/detalle.php";
    }

    public function buscarProductos($termino) {
        return $this->productoModel->buscar($termino);
    }

    public function obtenerProductosDestacados($limite = 4) {
        return $this->productoModel->getDestacados($limite);
    }

    public function obtenerProductosMasVendidos($limite = 4) {
        return $this->productoModel->getMasVendidos($limite);
    }

    public function obtenerProductosRecientes($limite = 4) {
        return $this->productoModel->getRecientes($limite);
    }

    public function agregarProducto($datos) {
        // Validación de datos
        if (empty($datos['nombre']) || empty($datos['precio']) || empty($datos['categoria_id'])) {
            return ['error' => 'Faltan datos obligatorios'];
        }

        // Lógica para agregar el producto
        $id = $this->productoModel->crear($datos);
        if ($id) {
            return ['success' => true, 'id' => $id];
        } else {
            return ['error' => 'No se pudo crear el producto'];
        }
    }

    public function actualizarProducto($id, $datos) {
        // Validación de datos
        if (empty($datos['nombre']) || empty($datos['precio']) || empty($datos['categoria_id'])) {
            return ['error' => 'Faltan datos obligatorios'];
        }

        // Lógica para actualizar el producto
        $actualizado = $this->productoModel->actualizar($id, $datos);
        if ($actualizado) {
            return ['success' => true];
        } else {
            return ['error' => 'No se pudo actualizar el producto'];
        }
    }

    public function eliminarProducto($id) {
        // Lógica para eliminar el producto
        $eliminado = $this->productoModel->eliminar($id);
        if ($eliminado) {
            return ['success' => true];
        } else {
            return ['error' => 'No se pudo eliminar el producto'];
        }
    }
}

