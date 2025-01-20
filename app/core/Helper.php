<?php
class Database {
    private static $host = "localhost"; // Servidor
    private static $dbname = "tienda-online"; // Nombre de la BD
    private static $user = "root"; // Usuario de la BD (cámbialo si usas otro)
    private static $pass = ""; // Contraseña de la BD (cámbiala si tienes una)
    private static $charset = "utf8mb4"; // Codificación
    private static $pdo = null; // Conexión PDO

    // Método para conectar a la base de datos
    public static function connect() {
        if (self::$pdo == null) {
            try {
                $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$dbname . ";charset=" . self::$charset;
                self::$pdo = new PDO($dsn, self::$user, self::$pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Modo de errores
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Formato de los resultados
                    PDO::ATTR_EMULATE_PREPARES => false, // Evita inyecciones SQL
                ]);
            } catch (PDOException $e) {
                die("Error de conexión: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
?>
