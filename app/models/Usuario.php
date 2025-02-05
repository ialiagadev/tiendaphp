<?php
require_once __DIR__ . "/../../config/db.php";

class Usuario {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    // 🔹 OBTENER TODOS LOS USUARIOS (ADMIN)
    public function getAll() {
        $stmt = $this->pdo->query("SELECT id, nombre, email, rol, activo, created_at FROM usuarios ORDER BY id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔹 REGISTRAR UN NUEVO USUARIO
    public function registrar($nombre, $email, $password, $rol = 'cliente') {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false; // Email inválido
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("
            INSERT INTO usuarios (nombre, email, password, rol, activo, created_at) 
            VALUES (:nombre, :email, :password, :rol, 1, NOW())
        ");
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $hashedPassword);
        $stmt->bindParam(":rol", $rol);
        return $stmt->execute();
    }

    // 🔹 INICIAR SESIÓN
    public function login($email, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = :email AND activo = 1");
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($password, $usuario['password'])) {
            return [
                'id' => $usuario['id'],
                'nombre' => $usuario['nombre'],
                'email' => $usuario['email'],
                'rol' => $usuario['rol']
            ];
        }
        return false;
    }

    // 🔹 OBTENER UN USUARIO POR ID
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT id, nombre, email, telefono, rol, activo, calle, ciudad, codigo_postal, pais FROM usuarios WHERE id = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 🔹 ACTUALIZAR USUARIO (ADMIN)
    public function actualizar($id, $nombre, $email, $telefono, $rol, $activo, $calle, $ciudad, $codigo_postal, $pais) {
        $stmt = $this->pdo->prepare("UPDATE usuarios 
            SET nombre = :nombre, email = :email, telefono = :telefono, rol = :rol, activo = :activo, 
                calle = :calle, ciudad = :ciudad, codigo_postal = :codigo_postal, pais = :pais 
            WHERE id = :id");
        
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":telefono", $telefono);
        $stmt->bindParam(":rol", $rol);
        $stmt->bindParam(":activo", $activo, PDO::PARAM_INT);
        $stmt->bindParam(":calle", $calle);
        $stmt->bindParam(":ciudad", $ciudad);
        $stmt->bindParam(":codigo_postal", $codigo_postal);
        $stmt->bindParam(":pais", $pais);
    
        return $stmt->execute();
    }
    

    // 🔹 ELIMINAR USUARIO (BAJA LÓGICA)
    public function eliminar($id) {
        $stmt = $this->pdo->prepare("UPDATE usuarios SET activo = 0 WHERE id = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // 🔹 REACTIVAR USUARIO ELIMINADO
    public function reactivar($id) {
        $stmt = $this->pdo->prepare("UPDATE usuarios SET activo = 1 WHERE id = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            return $stmt->rowCount() > 0; // Verifica si realmente se actualizó un registro
        }
        return false;
    }
    
    // 🔹 VERIFICAR SI UN EMAIL YA EXISTE
    public function emailExiste($email) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = :email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function crearUsuarioAdmin($nombre, $email, $password, $rol, $direccion, $telefono, $calle, $ciudad, $codigo_postal, $pais) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $this->pdo->prepare("INSERT INTO usuarios (nombre, email, password, rol, activo, created_at, direccion, telefono, calle, ciudad, codigo_postal, pais) 
                                     VALUES (:nombre, :email, :password, :rol, 1, NOW(), :direccion, :telefono, :calle, :ciudad, :codigo_postal, :pais)");
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $hashedPassword);
        $stmt->bindParam(":rol", $rol);
        $stmt->bindParam(":direccion", $direccion);
        $stmt->bindParam(":telefono", $telefono);
        $stmt->bindParam(":calle", $calle);
        $stmt->bindParam(":ciudad", $ciudad);
        $stmt->bindParam(":codigo_postal", $codigo_postal);
        $stmt->bindParam(":pais", $pais);
    
        return $stmt->execute();
    }
    
    
}
?>
