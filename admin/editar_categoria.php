<?php
session_start();
require_once "../app/controllers/CategoriaController.php";

// Verificar si el usuario tiene permisos de administrador
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$categoriaController = new CategoriaController();

// Verificar si el ID es válido
if (!isset($_GET["id"]) || empty($_GET["id"]) || !is_numeric($_GET["id"])) {
    $_SESSION["error"] = "❌ ID de categoría no válido.";
    header("Location: categorias.php");
    exit;
}

$id = intval($_GET["id"]);
$categoria = $categoriaController->obtenerCategoriaPorId($id);
$categoriasPadre = $categoriaController->obtenerCategoriasPadre();

if (!$categoria) {
    $_SESSION["error"] = "❌ La categoría no existe.";
    header("Location: categorias.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST["nombre"]);
    $descripcion = trim($_POST["descripcion"]);
    $padre_id = isset($_POST["padre_id"]) && $_POST["padre_id"] !== "" ? $_POST["padre_id"] : null;

    if ($categoriaController->actualizarCategoria($id, $nombre, $descripcion, $padre_id)) {
        header("Location: categorias.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Categoría</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .container {
            flex: 1;
        }
        footer {
            background: #f8f9fa;
            padding: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
<?php include("../app/components/admin_navbar.php"); ?>

<div class="container">
    <h1 class="text-center my-4">Editar Categoría</h1>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form method="POST" action="editar_categoria.php?id=<?= $id ?>">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre de la Categoría:</label>
            <input type="text" id="nombre" name="nombre" class="form-control" value="<?= htmlspecialchars($categoria['nombre']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción:</label>
            <textarea id="descripcion" name="descripcion" class="form-control"><?= htmlspecialchars($categoria['descripcion']) ?></textarea>
        </div>

        <div class="mb-3">
            <label for="padre_id" class="form-label">Categoría Padre (opcional):</label>
            <select id="padre_id" name="padre_id" class="form-select">
                <option value="">Sin categoría padre</option>
                <?php foreach ($categoriasPadre as $catPadre): ?>
                    <option value="<?= $catPadre['id'] ?>" <?= $categoria['padre_id'] == $catPadre['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($catPadre['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-success w-100">Actualizar Categoría</button>
    </form>
</div>

<footer>
    <?php include("../app/components/footer.php"); ?>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
