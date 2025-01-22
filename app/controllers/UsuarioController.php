<?php
require_once __DIR__ . "/../models/Usuario.php";
session_start();

class UsuarioController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    // Procesar registro
    public function registrar() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nombre = $_POST["nombre"];
            $email = $_POST["email"];
            $password = $_POST["password"];

            if ($this->usuarioModel->registrar($nombre, $email, $password)) {
                header("Location: login.php");
            } else {
                echo "Error al registrar usuario.";
            }
        }
        require_once __DIR__ . "/../views/registro.php";
    }

    // Procesar login
    public function login() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST["email"];
            $password = $_POST["password"];

            $usuario = $this->usuarioModel->login($email, $password);
            if ($usuario) {
                $_SESSION["usuario"] = $usuario;
                header("Location: index.php");
            } else {
                echo "Credenciales incorrectas.";
            }
        }
        require_once __DIR__ . "/../views/login.php";
    }

    // Cerrar sesión
    public function logout() {
        session_destroy();
        header("Location: login.php");
    }
}
?>
