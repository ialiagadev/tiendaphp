<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario es administrador
if (!isset($_SESSION["usuario"]) || $_SESSION["usuario"]["rol"] !== "admin") {
    header("Location: ../public/index.php");
    exit();
}

class AdminController {
    public function index() {
        require_once __DIR__ . "/../../admin/index.php";
    }
}
?>
