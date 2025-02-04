<?php
session_start();
require_once "../app/controllers/ProductoController.php";
require_once "../app/controllers/CategoriaController.php"; // Para obtener las categorías

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$productoController = new ProductoController();
$categoriaController = new CategoriaController();
$categorias = $categoriaController->obtenerCategorias(); // Obtener las categorías disponibles

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $categoria_id = $_POST['categoria_id'];

    // Manejo de la imagen
    $imagen = null;
    if (!empty($_FILES["imagen"]["name"])) {
        $imagenNombre = time() . "_" . basename($_FILES["imagen"]["name"]);
        $rutaImagen = "../public/img/" . $imagenNombre;
        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaImagen)) {
            $imagen = "img/" . $imagenNombre; // Ruta relativa para la base de datos
        }
    }

    $productoController->crearProducto($nombre, $descripcion, $precio, $stock, $categoria_id, $imagen);
    header("Location: productos.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Producto</title>
    <link rel="stylesheet" href="css/admin-styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <?php include "templates/header.php"; ?>

    <div class="container">
        <h1 class="text-center my-4">Añadir Nuevo Producto</h1>
        <form action="nuevo_producto.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Producto</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
            </div>

            <div class="mb-3">
                <label for="precio" class="form-label">Precio ($)</label>
                <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
            </div>

            <div class="mb-3">
                <label for="stock" class="form-label">Stock</label>
                <input type="number" class="form-control" id="stock" name="stock" required>
            </div>

            <div class="mb-3">
                <label for="categoria" class="form-label">Categoría</label>
                <select class="form-select" id="categoria" name="categoria_id" required>
                    <option value="">Selecciona una categoría</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?= $categoria['id'] ?>"><?= htmlspecialchars($categoria['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen del Producto</label>
                <input type="file" class="form-control" id="imagen" name="imagen">
            </div>

            <button type="submit" class="btn btn-primary w-100">Guardar Producto</button>
        </form>
    </div>

    <?php include "templates/footer.php"; ?>
</body>
</html>
