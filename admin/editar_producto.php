<?php
session_start();
require_once "../app/controllers/ProductoController.php";
require_once "../app/controllers/CategoriaController.php";

// Verificar que el usuario tiene rol de administrador
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$productoController = new ProductoController();
$categoriaController = new CategoriaController();

// Obtener ID del producto
if (!isset($_GET['id'])) {
    header("Location: productos.php");
    exit;
}

$id = $_GET['id'];
$producto = $productoController->obtenerProductoPorId($id);
$categorias = $categoriaController->obtenerCategorias();

if (!$producto) {
    echo "❌ Producto no encontrado.";
    exit;
}

// Procesar la actualización del producto
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $datos = [
        'nombre' => $_POST["nombre"],
        'precio' => $_POST["precio"],
        'stock' => $_POST["stock"],
        'categoria_id' => $_POST["categoria_id"],
        'descripcion' => $_POST["descripcion"],
        'activo' => isset($_POST["activo"]) ? 1 : 0
    ];

    // Manejo de la imagen
    if (!empty($_FILES["imagen"]["name"])) {
        $imagenNombre = basename($_FILES["imagen"]["name"]);
        $rutaImagen = "../public/img/" . $imagenNombre;

        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaImagen)) {
            $datos['imagen'] = "img/" . $imagenNombre;
        }
    } else {
        $datos['imagen'] = $producto['imagen'];
    }

    $resultado = $productoController->actualizarProducto($id, $datos);

    if (isset($resultado['success'])) {
        header("Location: productos.php?success=✅ Producto actualizado correctamente.");
        exit;
    } else {
        $error = $resultado['error'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<?php include("../app/components/admin_navbar.php"); ?>

<div class="container">
    <h1 class="text-center my-4">✏️ Editar Producto</h1>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="editar_producto.php?id=<?= $id ?>" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nombre" class="form-label">📝 Nombre del Producto:</label>
            <input type="text" id="nombre" name="nombre" class="form-control" value="<?= htmlspecialchars($producto['nombre']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="precio" class="form-label">💰 Precio:</label>
            <input type="number" step="0.01" id="precio" name="precio" class="form-control" value="<?= htmlspecialchars($producto['precio']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="stock" class="form-label">📦 Stock:</label>
            <input type="number" id="stock" name="stock" class="form-control" value="<?= htmlspecialchars($producto['stock']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="categoria_id" class="form-label">📂 Categoría:</label>
            <select id="categoria_id" name="categoria_id" class="form-select" required>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?= $categoria['id'] ?>" <?= ($producto['categoria_id'] == $categoria['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($categoria['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">📜 Descripción:</label>
            <textarea id="descripcion" name="descripcion" class="form-control"><?= htmlspecialchars($producto['descripcion']) ?></textarea>
        </div>

        <div class="mb-3">
            <label for="imagen" class="form-label">🖼️ Imagen:</label>
            <input type="file" id="imagen" name="imagen" class="form-control">
            <?php if (!empty($producto['imagen'])): ?>
                <img src="../public/<?= htmlspecialchars($producto['imagen']) ?>" class="img-thumbnail mt-2" style="max-width: 200px;" alt="Imagen actual">
            <?php endif; ?>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" id="activo" name="activo" class="form-check-input" <?= $producto['activo'] ? 'checked' : '' ?>>
            <label for="activo" class="form-check-label">✔️ Producto Activo</label>
        </div>

        <button type="submit" class="btn btn-primary w-100">💾 Guardar Cambios</button>
    </form>
</div>

<?php include("../app/components/footer.php"); ?>
</body>
</html>
