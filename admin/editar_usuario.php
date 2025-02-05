<?php
session_start();
require_once "../app/controllers/UsuarioController.php";

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$usuarioController = new UsuarioController();
$usuario = null;

if (isset($_GET["id"])) {
    $usuario = $usuarioController->obtenerUsuarioPorId($_GET["id"]);
    if (!$usuario) {
        die("❌ Usuario no encontrado.");
    }
} else {
    die("❌ ID de usuario no proporcionado.");
}

// Procesar la actualización
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $resultado = $usuarioController->actualizarUsuario();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<?php include("../app/components/navbar.php"); ?>

<div class="container">
    <h1 class="text-center my-4">Editar Usuario</h1>

    <?php if (isset($resultado) && is_array($resultado)): ?>
        <div class="alert <?= isset($resultado['error']) ? 'alert-danger' : 'alert-success' ?>">
            <?= isset($resultado['error']) ? $resultado['error'] : '✅ Usuario actualizado con éxito.' ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">✅ Usuario actualizado con éxito.</div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger">❌ Hubo un error al actualizar el usuario.</div>
<?php endif; ?>

    <form method="POST" action="editar_usuario.php?id=<?= htmlspecialchars($usuario['id']) ?>">
        <input type="hidden" name="id" value="<?= htmlspecialchars($usuario['id']) ?>">

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" id="nombre" name="nombre" class="form-control" 
                       value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-control" 
                       value="<?= htmlspecialchars($usuario['email']) ?>" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="telefono" class="form-label">Teléfono:</label>
                <input type="text" id="telefono" name="telefono" class="form-control" 
                       value="<?= isset($usuario['telefono']) ? htmlspecialchars($usuario['telefono']) : '' ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label for="rol" class="form-label">Rol:</label>
                <select id="rol" name="rol" class="form-select" required>
                    <option value="cliente" <?= $usuario['rol'] === 'cliente' ? 'selected' : '' ?>>Cliente</option>
                    <option value="empleado" <?= $usuario['rol'] === 'empleado' ? 'selected' : '' ?>>Empleado</option>
                    <option value="admin" <?= $usuario['rol'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="activo" class="form-label">Estado:</label>
                <select id="activo" name="activo" class="form-select">
                    <option value="1" <?= $usuario['activo'] == 1 ? 'selected' : '' ?>>Activo</option>
                    <option value="0" <?= $usuario['activo'] == 0 ? 'selected' : '' ?>>Inactivo</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="calle" class="form-label">Calle:</label>
                <input type="text" id="calle" name="calle" class="form-control" 
                       value="<?= isset($usuario['calle']) ? htmlspecialchars($usuario['calle']) : '' ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label for="ciudad" class="form-label">Ciudad:</label>
                <input type="text" id="ciudad" name="ciudad" class="form-control" 
                       value="<?= isset($usuario['ciudad']) ? htmlspecialchars($usuario['ciudad']) : '' ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label for="codigo_postal" class="form-label">Código Postal:</label>
                <input type="text" id="codigo_postal" name="codigo_postal" class="form-control" 
                       value="<?= isset($usuario['codigo_postal']) ? htmlspecialchars($usuario['codigo_postal']) : '' ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label for="pais" class="form-label">País:</label>
                <input type="text" id="pais" name="pais" class="form-control" 
                       value="<?= isset($usuario['pais']) ? htmlspecialchars($usuario['pais']) : '' ?>">
            </div>
        </div>

        <button type="submit" class="btn btn-success w-100">Actualizar Usuario</button>
    </form>

    <div class="mt-3">
        <a href="usuarios.php" class="btn btn-secondary">Volver a la lista de usuarios</a>
    </div>
</div>

<?php include("../app/components/footer.php"); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
