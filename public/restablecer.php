<?php
require_once "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $nueva_contraseña = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Conectar a la BD y buscar el usuario
    $pdo = Database::connect();
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email");
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Si el usuario existe, actualizar la contraseña
        $stmt = $pdo->prepare("UPDATE usuarios SET password = :password WHERE email = :email");
        $stmt->bindParam(":password", $nueva_contraseña);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        echo "<p class='alert alert-success'>✅ Contraseña actualizada con éxito. Puedes iniciar sesión.</p>";
    } else {
        echo "<p class='alert alert-danger'>❌ No existe una cuenta con ese correo.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>🔑 Restablecer Contraseña</h2>
        <form method="POST">
            <div class="mb-3">
                <label>Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Nueva Contraseña:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Actualizar Contraseña</button>
        </form>

        <!-- Botón de Iniciar Sesión -->
        <div class="text-center mt-3">
            <a href="login.php" class="btn btn-primary">Iniciar Sesión</a>
        </div>
    </div>
</body>
</html>
