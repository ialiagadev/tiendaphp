<?php
require_once "../config/db.php"; // Incluir la conexión

try {
    $db = Database::connect();
    echo "✅ Conexión exitosa a la base de datos.";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
