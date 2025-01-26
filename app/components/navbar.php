<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/index.php"><i class="fas fa-store me-2"></i>Tienda Online</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="carrito.php"><i class="fas fa-shopping-cart me-1"></i>Carrito</a></li>
                <?php if (isset($_SESSION['usuario'])): ?>
                    <li class="nav-item"><span class="nav-link"><i class="fas fa-user me-1"></i>Hola, <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?></span></li>
                    <li class="nav-item"><a class="nav-link" href="/logout.php"><i class="fas fa-sign-out-alt me-1"></i>Cerrar Sesión</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="/login.php"><i class="fas fa-sign-in-alt me-1"></i>Iniciar Sesión</a></li>
                    <li class="nav-item"><a class="nav-link" href="/registro.php"><i class="fas fa-user-plus me-1"></i>Registrarse</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

