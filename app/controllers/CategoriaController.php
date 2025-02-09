<?php
require_once __DIR__ . "/../models/Categoria.php";
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class CategoriaController {
    private $categoriaModel;

    public function __construct() {
        $this->categoriaModel = new Categoria();
    }

    // 🔹 Obtener todas las categorías con subcategorías
    public function obtenerCategorias() {
        return $this->categoriaModel->getAllWithSubcategories();
    }

    // 🔹 Obtener una categoría por ID
    public function obtenerCategoriaPorId($id) {
        return $this->categoriaModel->getById($id);
    }

    // 🔹 Obtener todas las categorías principales (sin padre)
    public function obtenerCategoriasPadre() {
        return $this->categoriaModel->getMainCategories();
    }

    // 🔹 Crear una nueva categoría
    public function crearCategoria($nombre, $descripcion, $padre_id = null) {
        if (empty($nombre)) {
            $_SESSION["error"] = "❌ El nombre de la categoría es obligatorio.";
            return false;
        }

        if ($this->categoriaModel->crearCategoria($nombre, $descripcion, $padre_id)) {
            $_SESSION["success"] = "✅ Categoría creada correctamente.";
            return true;
        } else {
            $_SESSION["error"] = "❌ No se pudo crear la categoría.";
            return false;
        }
    }

    // 🔹 Actualizar una categoría existente
    public function actualizarCategoria($id, $nombre, $descripcion, $padre_id = null) {
        if (empty($nombre)) {
            $_SESSION["error"] = "❌ El nombre de la categoría es obligatorio.";
            return false;
        }

        if ($this->categoriaModel->actualizarCategoria($id, $nombre, $descripcion, $padre_id)) {
            $_SESSION["success"] = "✅ Categoría actualizada correctamente.";
            return true;
        } else {
            $_SESSION["error"] = "❌ No se pudo actualizar la categoría.";
            return false;
        }
    }

    // 🔹 Eliminar categoría (baja lógica)
    public function eliminarCategoria($id) {
        if ($this->categoriaModel->hasSubcategories($id)) {
            $_SESSION["error"] = "❌ No se puede eliminar una categoría con subcategorías activas.";
            return false;
        }

        if ($this->categoriaModel->eliminarCategoria($id)) {
            $_SESSION["success"] = "✅ Categoría eliminada correctamente.";
            return true;
        } else {
            $_SESSION["error"] = "❌ No se pudo eliminar la categoría.";
            return false;
        }
    }
}
?>
