<?php
require_once __DIR__ . "/../../config/db.php";

class Categoria {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    // Obtener todas las categorías principales (sin padre)
    public function getMainCategories() {
        $stmt = $this->pdo->prepare("
            SELECT id, nombre, descripcion 
            FROM categorias 
            WHERE padre_id IS NULL AND activo = 1
            ORDER BY nombre
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener subcategorías de una categoría específica
    public function getSubcategories($padre_id) {
        $stmt = $this->pdo->prepare("
            SELECT id, nombre, descripcion 
            FROM categorias 
            WHERE padre_id = :padre_id AND activo = 1
            ORDER BY nombre
        ");
        $stmt->bindParam(":padre_id", $padre_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener todas las categorías con sus subcategorías
    public function getAllWithSubcategories() {
        // Obtener todas las categorías
        $stmt = $this->pdo->prepare("
            SELECT id, nombre, descripcion, padre_id 
            FROM categorias 
            WHERE activo = 1 
            ORDER BY 
                CASE WHEN padre_id IS NULL THEN 0 ELSE 1 END,
                padre_id,
                nombre
        ");
        $stmt->execute();
        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Organizar en jerarquía
        $categoriasOrganizadas = [];
        $subcategorias = [];

        foreach ($categorias as $categoria) {
            if ($categoria['padre_id'] === null) {
                $categoriasOrganizadas[$categoria['id']] = $categoria;
                $categoriasOrganizadas[$categoria['id']]['subcategorias'] = [];
            } else {
                $subcategorias[] = $categoria;
            }
        }

        // Añadir subcategorías a sus respectivas categorías padre
        foreach ($subcategorias as $subcategoria) {
            if (isset($categoriasOrganizadas[$subcategoria['padre_id']])) {
                $categoriasOrganizadas[$subcategoria['padre_id']]['subcategorias'][] = $subcategoria;
            }
        }

        return $categoriasOrganizadas;
    }

    // Obtener una categoría por ID
    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT c.*, p.nombre as categoria_padre 
            FROM categorias c 
            LEFT JOIN categorias p ON c.padre_id = p.id 
            WHERE c.id = :id AND c.activo = 1
        ");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Verificar si una categoría tiene subcategorías
    public function hasSubcategories($categoria_id) {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) 
            FROM categorias 
            WHERE padre_id = :categoria_id AND activo = 1
        ");
        $stmt->bindParam(":categoria_id", $categoria_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
}

