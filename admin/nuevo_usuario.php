<?php
session_start();
require_once "../app/controllers/UsuarioController.php";

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$usuarioController = new UsuarioController();
$resultado = null; // Inicializa la variable para evitar errores

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $resultado = $usuarioController->crearUsuarioAdmin(); // Llamamos a la función correcta
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Usuario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<?php include("../app/components/admin_navbar.php"); ?>

<div class="container">
    <h1 class="text-center my-4">Registrar Nuevo Usuario</h1>

    <!-- Mensaje de éxito o error -->
    <?php if ($resultado): ?>
        <div class="alert <?= isset($resultado['error']) ? 'alert-danger' : 'alert-success' ?>">
            <?= isset($resultado['error']) ? $resultado['error'] : '✅ Usuario registrado con éxito.' ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="nuevo_usuario.php">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" id="nombre" name="nombre" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="telefono" class="form-label">Teléfono:</label>
                <input type="text" id="telefono" name="telefono" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label for="rol" class="form-label">Rol:</label>
                <select id="rol" name="rol" class="form-select" required>
                    <option value="cliente">Cliente</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="activo" class="form-label">Estado:</label>
                <select id="activo" name="activo" class="form-select">
                    <option value="1" selected>Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="calle" class="form-label">Calle:</label>
                <input type="text" id="calle" name="calle" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label for="ciudad" class="form-label">Ciudad:</label>
                <input type="text" id="ciudad" name="ciudad" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label for="codigo_postal" class="form-label">Código Postal:</label>
                <input type="text" id="codigo_postal" name="codigo_postal" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label for="pais" class="form-label">País:</label>
                <input type="text" id="pais" name="pais" class="form-control">
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">Registrar Usuario</button>
    </form>

    <div class="mt-3 text-center">
        <a href="usuarios.php" class="btn btn-secondary">Volver a Gestión de Usuarios</a>
    </div>
</div>

<?php include("../app/components/footer.php"); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
