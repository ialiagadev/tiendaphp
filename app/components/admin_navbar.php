<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">🛠 Admin Panel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Menú">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="usuarios.php">👤 Usuarios</a></li>
                <li class="nav-item"><a class="nav-link" href="productos.php">📦 Productos</a></li>
                <li class="nav-item"><a class="nav-link" href="categorias.php">📂 Categorías</a></li>
                <li class="nav-item"><a class="nav-link" href="informes.php">📊 Informes</a></li>
            </ul>

            <ul class="navbar-nav">
    <?php if (isset($_SESSION['usuario'])): ?>
        <li class="nav-item">
            <span class="navbar-text text-light me-3">👋 Bienvenido, <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?></span>
        </li>
        <li class="nav-item">
            <a class="btn btn-danger" href="../public/logout.php">🚪 Cerrar sesión</a>
        </li>
    <?php else: ?>
        <li class="nav-item">
            <a class="nav-link" href="../public/login.php">🔐 Iniciar sesión</a>
        </li>
    <?php endif; ?>
</ul>

        </div>
    </div>
</nav>
