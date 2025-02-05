<?php
session_start();
require_once "../app/controllers/UsuarioController.php";

// Verificar que el usuario tiene rol de administrador
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$controller = new UsuarioController();
$usuarios = $controller->obtenerUsuarios();

// Manejo de eliminación y reactivación de usuarios
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["eliminar_usuario"])) {
        $id = $_POST["id_usuario"];
        if ($controller->eliminarUsuarioPorAdmin($id)) {
            $_SESSION['success'] = "✅ Usuario eliminado correctamente.";
        } else {
            $_SESSION['error'] = "❌ No se pudo eliminar el usuario.";
        }
    } elseif (isset($_POST["reactivar_usuario"])) {
        $id = $_POST["id_usuario"];
        if ($controller->reactivarUsuarioPorAdmin($id)) {
            $_SESSION['success'] = "✅ Usuario reactivado correctamente.";
        } else {
            $_SESSION['error'] = "❌ No se pudo reactivar el usuario.";
        }
    }
    header("Location: usuarios.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="css/admin-styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <?php include("../app/components/navbar.php"); ?>

    <div class="container">
        <h1 class="text-center my-4">Gestión de Usuarios</h1>

        <!-- Mensajes de éxito o error -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Botón para añadir un nuevo usuario -->
        <a href="nuevo_usuario.php" class="btn btn-primary mb-3">Nuevo Usuario</a>

        <!-- Barra de búsqueda -->
        <input type="text" id="searchBar" class="form-control mb-3" placeholder="Buscar usuarios...">

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="userList">
                <?php foreach ($usuarios as $usuario): ?>
                    <tr data-name="<?= htmlspecialchars($usuario['nombre']) ?>" 
                        data-email="<?= htmlspecialchars($usuario['email']) ?>" 
                        data-role="<?= htmlspecialchars($usuario['rol']) ?>">
                        
                        <td><?= $usuario['id'] ?></td>
                        <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                        <td><?= htmlspecialchars($usuario['email']) ?></td>
                        <td><?= !empty($usuario['rol']) ? ucfirst($usuario['rol']) : '<span class="text-danger">❌ No asignado</span>' ?></td>
                        <td><?= $usuario['activo'] ? "✅ Activo" : "❌ Inactivo" ?></td>
                        <td>
                            <a href="editar_usuario.php?id=<?= $usuario['id'] ?>" class="btn btn-warning btn-sm">Editar</a>

                            <?php if ($usuario['activo']): ?>
                                <form method="POST" action="" class="d-inline">
                                    <input type="hidden" name="id_usuario" value="<?= $usuario['id'] ?>">
                                    <button type="submit" name="eliminar_usuario" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que quieres eliminar este usuario?')">Eliminar</button>
                                </form>
                            <?php else: ?>
                                <form method="POST" action="" class="d-inline">
                                    <input type="hidden" name="id_usuario" value="<?= $usuario['id'] ?>">
                                    <button type="submit" name="reactivar_usuario" class="btn btn-success btn-sm">Reactivar</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php include("../app/components/footer.php"); ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchBar = document.getElementById('searchBar');
            const userList = document.getElementById('userList').getElementsByTagName('tr');

            searchBar.addEventListener('input', function() {
                const searchTerm = searchBar.value.toLowerCase();
                for (let user of userList) {
                    const userName = user.getAttribute('data-name').toLowerCase();
                    const userEmail = user.getAttribute('data-email').toLowerCase();
                    const userRole = user.getAttribute('data-role').toLowerCase();

                    if (userName.includes(searchTerm) || userEmail.includes(searchTerm) || userRole.includes(searchTerm)) {
                        user.style.display = '';
                    } else {
                        user.style.display = 'none';
                    }
                }
            });
        });
    </script>
</body>
</html>
