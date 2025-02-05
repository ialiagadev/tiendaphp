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

    // 🔹 Crear una nueva categoría
    public function crearCategoria() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nombre = trim($_POST["nombre"]);
            $descripcion = trim($_POST["descripcion"]);
            $padre_id = !empty($_POST["padre_id"]) ? $_POST["padre_id"] : null;

            if (empty($nombre)) {
                $_SESSION["error"] = "❌ El nombre de la categoría es obligatorio.";
                header("Location: ../admin/nueva_categoria.php");
                exit();
            }

            if ($this->categoriaModel->crearCategoria($nombre, $descripcion, $padre_id)) {
                $_SESSION["success"] = "✅ Categoría creada correctamente.";
                header("Location: ../admin/categorias.php");
                exit();
            } else {
                $_SESSION["error"] = "❌ No se pudo crear la categoría.";
                header("Location: ../admin/nueva_categoria.php");
                exit();
            }
        }
    }

    // 🔹 Actualizar una categoría existente
    public function actualizarCategoria() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
            $id = $_POST["id"];
            $nombre = trim($_POST["nombre"]);
            $descripcion = trim($_POST["descripcion"]);
            $padre_id = !empty($_POST["padre_id"]) ? $_POST["padre_id"] : null;

            if (empty($nombre)) {
                $_SESSION["error"] = "❌ El nombre de la categoría es obligatorio.";
                header("Location: ../admin/editar_categoria.php?id=$id");
                exit();
            }

            if ($this->categoriaModel->actualizarCategoria($id, $nombre, $descripcion, $padre_id)) {
                $_SESSION["success"] = "✅ Categoría actualizada correctamente.";
                header("Location: ../admin/categorias.php");
                exit();
            } else {
                $_SESSION["error"] = "❌ No se pudo actualizar la categoría.";
                header("Location: ../admin/editar_categoria.php?id=$id");
                exit();
            }
        }
    }

    // 🔹 Eliminar categoría (baja lógica)
    public function eliminarCategoria() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
            $id = $_POST["id"];

            if ($this->categoriaModel->hasSubcategories($id)) {
                $_SESSION["error"] = "❌ No se puede eliminar una categoría con subcategorías.";
                header("Location: ../admin/categorias.php");
                exit();
            }

            if ($this->categoriaModel->eliminarCategoria($id)) {
                $_SESSION["success"] = "✅ Categoría eliminada correctamente.";
                header("Location: ../admin/categorias.php");
                exit();
            } else {
                $_SESSION["error"] = "❌ No se pudo eliminar la categoría.";
                header("Location: ../admin/categorias.php");
                exit();
            }
        }
    }

    // 🔹 Reactivar categoría eliminada
    public function reactivarCategoria() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
            $id = $_POST["id"];

            if ($this->categoriaModel->reactivarCategoria($id)) {
                $_SESSION["success"] = "✅ Categoría reactivada correctamente.";
                header("Location: ../admin/categorias.php");
                exit();
            } else {
                $_SESSION["error"] = "❌ No se pudo reactivar la categoría.";
                header("Location: ../admin/categorias.php");
                exit();
            }
        }
    }
}
?>
