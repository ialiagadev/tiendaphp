<?php
require_once "../config/db.php";

$pdo = Database::connect();
$email = 'admin0@gmail.com';
$nueva_contraseña = 'admin123'; // Contraseña en texto plano

// Hashear la nueva contraseña correctamente
$hashedPassword = password_hash($nueva_contraseña, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("UPDATE usuarios SET password = :password WHERE email = :email");
$stmt->bindParam(':password', $hashedPassword);
$stmt->bindParam(':email', $email);

if ($stmt->execute()) {
    echo "✅ Contraseña actualizada correctamente para $email";
} else {
    echo "❌ Error al actualizar la contraseña.";
}
?>
