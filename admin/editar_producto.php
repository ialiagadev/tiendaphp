<?php
session_start();
require_once "../app/controllers/ProductoController.php";
require_once "../app/controllers/CategoriaController.php";

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$productoController = new ProductoController();
$categoriaController = new CategoriaController();
$categorias = $categoriaController->obtenerCategorias(); // Obtener las categorías

// Verificar si el ID del producto está presente en la URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: productos.php");
    exit;
}

$producto_id = $_GET['id'];
$producto = $productoController->obtenerProductoPorId($producto_id);

if (!$producto) {
    echo "<p class='alert alert-danger'>Producto no encontrado.</p>";
    exit;
}

// Procesar el formulario si se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $categoria_id = $_POST['categoria_id'];

    // Manejo de imagen (solo si el admin sube una nueva)
    $imagen = $producto['imagen']; // Mantener la imagen existente por defecto
    if (!empty($_FILES["imagen"]["name"])) {
        $imagenNombre = time() . "_" . basename($_FILES["imagen"]["name"]);
        $rutaImagen = "../public/img/" . $imagenNombre;
        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaImagen)) {
            $imagen = "img/" . $imagenNombre; // Guardar la nueva imagen en la base de datos
        }
    }

    $productoController->actualizarProducto($producto_id, $nombre, $descripcion, $precio, $stock, $categoria_id, $imagen);
    header("Location: productos.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="css/admin-styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <?php include "templates/header.php"; ?>

    <div class="container">
        <h1 class="text-center my-4">Editar Producto</h1>
        <form action="editar_producto.php?id=<?= $producto['id'] ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Producto</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?= htmlspecialchars($producto['descripcion']) ?></textarea>
            </div>

            <div class="mb-3">
                <label for="precio" class="form-label">Precio ($)</label>
                <input type="number" step="0.01" class="form-control" id="precio" name="precio" value="<?= $producto['precio'] ?>" required>
            </div>

            <div class="mb-3">
                <label for="stock" class="form-label">Stock</label>
                <input type="number" class="form-control" id="stock" name="stock" value="<?= $producto['stock'] ?>" required>
            </div>

            <div class="mb-3">
                <label for="categoria" class="form-label">Categoría</label>
                <select class="form-select" id="categoria" name="categoria_id" required>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?= $categoria['id'] ?>" <?= ($categoria['id'] == $producto['categoria_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($categoria['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen del Producto (Opcional)</label>
                <input type="file" class="form-control" id="imagen" name="imagen">
                <?php if (!empty($producto['imagen'])): ?>
                    <p>Imagen actual:</p>
                    <img src="../public/<?= $producto['imagen'] ?>" alt="Imagen del producto" width="150">
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-success w-100">Actualizar Producto</button>
        </form>
    </div>

    <?php include "templates/footer.php"; ?>
</body>
</html>
/*  Explicación del Código
Verificación de acceso

Si el usuario no es administrador, se redirige a index.php.
Carga los datos del producto y las categorías

Se obtiene el ID del producto desde $_GET['id'] y se recuperan los datos.
Si el producto no existe, muestra un mensaje de error.
Formulario precargado

Se llenan los campos con la información actual del producto.
Los administradores pueden modificar cualquier campo.
Carga de imágenes (Opcional)

Si no se sube una nueva imagen, se mantiene la existente.
Si se sube una nueva imagen, se guarda en ../public/img/ y se actualiza en la base de datos.
Actualización en la base de datos

Al enviar el formulario, se llama a actualizarProducto() en el controlador.
Redirige a productos.php tras la actualización.
*/