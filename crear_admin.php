<?php
include 'config.php';

$nombre = "Administrador";
$email = "admin@tienda.com";
$password = password_hash("admin123", PASSWORD_DEFAULT);
$rol = "administrador";

$stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)");
$stmt->execute([$nombre, $email, $password, $rol]);

echo "Usuario administrador creado con éxito.";
?>
