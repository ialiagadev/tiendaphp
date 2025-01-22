<?php
require_once __DIR__ . "/../../config/db.php";

class Usuario {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    // Registrar un nuevo usuario
    public function registrar($nombre, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (:nombre, :email, :password)");
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $hashedPassword);
        return $stmt->execute();
    }

    // Iniciar sesión
    public function login($email, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = :email AND activo = 1");
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($password, $usuario['password'])) {
            return $usuario;
        }
        return false;
    }
}
?>
