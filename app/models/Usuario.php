<?php
require_once __DIR__ . "/../../config/db.php";

class Usuario {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    // Registrar un nuevo usuario con contraseña hasheada
    public function registrar($nombre, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO usuarios (nombre, email, password, rol, activo, created_at) 
                                     VALUES (:nombre, :email, :password, 'cliente', 1, NOW())");
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
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
          

            if (password_verify($password, $usuario['password'])) {
                return [
                    'id' => $usuario['id'],
                    'nombre' => $usuario['nombre'],
                    'email' => $usuario['email'],
                    'rol' => $usuario['rol']
                ];
            }
        }
        return false;
    }
}
?>
