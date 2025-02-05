<?php
session_start();
require_once "../app/controllers/CategoriaController.php";

// Verificar si el usuario tiene el rol de administrador
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$categoriaController = new CategoriaController();
$categoriasPadre = $categoriaController->obtenerCategoriasPadre(); // Obtener categorías principales

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST["nombre"]);
    $descripcion = trim($_POST["descripcion"]);
    $padre_id = isset($_POST["padre_id"]) && $_POST["padre_id"] !== "" ? $_POST["padre_id"] : null;

    $resultado = $categoriaController->crearCategoria($nombre, $descripcion, $padre_id);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Nueva Categoría</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<?php include("../app/components/navbar.php"); ?>

<div class="container">
    <h1 class="text-center my-4">Crear Nueva Categoría</h1>

    <?php if (isset($resultado) && is_array($resultado)): ?>
        <div class="alert <?= isset($resultado['error']) ? 'alert-danger' : 'alert-success' ?>">
            <?= isset($resultado['error']) ? $resultado['error'] : '✅ Categoría creada con éxito.' ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="nueva_categoria.php">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre de la Categoría:</label>
            <input type="text" id="nombre" name="nombre" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción:</label>
            <textarea id="descripcion" name="descripcion" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label for="padre_id" class="form-label">Categoría Padre (opcional):</label>
            <select id="padre_id" name="padre_id" class="form-select">
                <option value="">Sin categoría padre</option>
                <?php foreach ($categoriasPadre as $categoria): ?>
                    <option value="<?= $categoria['id'] ?>"><?= htmlspecialchars($categoria['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary w-100">Crear Categoría</button>
    </form>
</div>

<?php include("../app/components/footer.php"); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
