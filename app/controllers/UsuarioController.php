<?php
require_once __DIR__ . "/../models/Usuario.php";
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class UsuarioController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    // 🔹 REGISTRAR USUARIO NORMAL
    public function registrar() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nombre = trim($_POST["nombre"]);
            $email = trim($_POST["email"]);
            $password = trim($_POST["password"]);

            if (empty($nombre) || empty($email) || empty($password)) {
                echo "❌ Todos los campos son obligatorios.";
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "❌ Formato de email inválido.";
                return;
            }

            if ($this->usuarioModel->registrar($nombre, $email, $password)) {
                header("Location: login.php");
                exit();
            } else {
                echo "❌ Error al registrar usuario.";
            }
        }
        require_once __DIR__ . "/../views/registro.php";
    }

    // 🔹 INICIAR SESIÓN
    public function login() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = trim($_POST["email"]);
            $password = trim($_POST["password"]);

            if (empty($email) || empty($password)) {
                echo "❌ Email y contraseña son obligatorios.";
                return;
            }

            $usuario = $this->usuarioModel->login($email, $password);

            if ($usuario) {
                $_SESSION["usuario"] = $usuario;

                // Redirigir según el rol del usuario
                if ($usuario['rol'] === 'admin') {
                    header("Location: ../public/index.php");
                } else {
                    header("Location: ../public/index.php");
                }
                exit();
            } else {
                echo "❌ Credenciales incorrectas.";
            }
        }
        require_once __DIR__ . "/../views/login.php";
    }

    // 🔹 OBTENER TODOS LOS USUARIOS (ADMIN)
    public function obtenerUsuarios() {
        return $this->usuarioModel->getAll();
    }

    // 🔹 OBTENER USUARIO POR ID
    public function obtenerUsuarioPorId($id) {
        return $this->usuarioModel->getById($id);
    }

    // 🔹 ACTUALIZAR USUARIO (ADMIN)
    public function actualizarUsuario() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
            $id = trim($_POST["id"]);
            $nombre = trim($_POST["nombre"]);
            $email = trim($_POST["email"]);
            $telefono = trim($_POST["telefono"]);
            $rol = trim($_POST["rol"]);
            $activo = isset($_POST["activo"]) ? (int)$_POST["activo"] : 1;
            $calle = trim($_POST["calle"]);
            $ciudad = trim($_POST["ciudad"]);
            $codigo_postal = trim($_POST["codigo_postal"]);
            $pais = trim($_POST["pais"]);

            if (empty($nombre) || empty($email) || empty($rol)) {
                header("Location: ../admin/editar_usuario.php?id=$id&error=Faltan datos obligatorios.");
                exit();
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                header("Location: ../admin/editar_usuario.php?id=$id&error=Email inválido.");
                exit();
            }

            if ($this->usuarioModel->actualizar($id, $nombre, $email, $telefono, $rol, $activo, $calle, $ciudad, $codigo_postal, $pais)) {
                header("Location: ../admin/usuarios.php?success=Usuario actualizado correctamente.");
                exit();
            } else {
                header("Location: ../admin/editar_usuario.php?id=$id&error=No se pudo actualizar el usuario.");
                exit();
            }
        }
    }

    // 🔹 ELIMINAR USUARIO POR ADMIN (BAJA LÓGICA)
    public function eliminarUsuarioPorAdmin($id) {
        if (!is_numeric($id)) {
            return false;
        }
        
        return $this->usuarioModel->eliminar($id);
    }

    // 🔹 REACTIVAR USUARIO
    public function reactivarUsuarioPorAdmin() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_usuario"])) {
            $id = $_POST["id_usuario"];
            
            if ($this->usuarioModel->reactivar($id)) {
                $_SESSION['success'] = "✅ Usuario reactivado correctamente.";
            } else {
                $_SESSION['error'] = "❌ No se pudo reactivar el usuario.";
            }
        }
        header("Location: ../admin/usuarios.php");
        exit();
    }

    public function crearUsuarioAdmin() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nombre = trim($_POST["nombre"]);
            $email = trim($_POST["email"]);
            $password = trim($_POST["password"]);
            $rol = trim($_POST["rol"]);
            $direccion = trim($_POST["direccion"] ?? '');
            $telefono = trim($_POST["telefono"] ?? '');
            $calle = trim($_POST["calle"] ?? '');
            $ciudad = trim($_POST["ciudad"] ?? '');
            $codigo_postal = trim($_POST["codigo_postal"] ?? '');
            $pais = trim($_POST["pais"] ?? '');

            if (empty($nombre) || empty($email) || empty($password) || empty($rol)) {
                $_SESSION['error'] = "❌ Todos los campos son obligatorios.";
                header("Location: ../admin/nuevo_usuario.php");
                exit();
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = "❌ Formato de email inválido.";
                header("Location: ../admin/nuevo_usuario.php");
                exit();
            }

            $roles_validos = ['cliente', 'empleado', 'admin'];
            if (!in_array($rol, $roles_validos)) {
                $_SESSION['error'] = "❌ Rol no permitido.";
                header("Location: ../admin/nuevo_usuario.php");
                exit();
            }

            if ($this->usuarioModel->crearUsuarioAdmin($nombre, $email, $password, $rol, $direccion, $telefono, $calle, $ciudad, $codigo_postal, $pais)) {
                $_SESSION['success'] = "✅ Usuario creado exitosamente.";
                header("Location: ../admin/usuarios.php");
                exit();
            } else {
                $_SESSION['error'] = "❌ No se pudo crear el usuario.";
                header("Location: ../admin/nuevo_usuario.php");
                exit();
            }
        }
    }

    // 🔹 CERRAR SESIÓN
    public function logout() {
        session_destroy();
        header("Location: login.php");
        exit();
    }
}
