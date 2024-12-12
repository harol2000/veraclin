<?php
include 'config.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Validar si el ID del proveedor se ha pasado como parámetro
if (!isset($_GET['id'])) {
    die("Error: No se ha especificado un proveedor para eliminar.");
}

$proveedor_id = $_GET['id'];

try {
    // Verificar que el proveedor exista
    $stmt = $conn->prepare("SELECT * FROM proveedores WHERE id = ?");
    $stmt->execute([$proveedor_id]);
    $proveedor = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$proveedor) {
        die("Error: El proveedor no existe.");
    }

    // Eliminar el proveedor
    $stmt = $conn->prepare("DELETE FROM proveedores WHERE id = ?");
    $stmt->execute([$proveedor_id]);

    // Redirigir a la página de proveedores con un mensaje de éxito
    header("Location: proveedores.php?mensaje=Proveedor eliminado exitosamente.");
    exit();
} catch (PDOException $e) {
    die("Error al eliminar el proveedor: " . $e->getMessage());
}
?>
