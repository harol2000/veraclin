<?php
include 'config.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Validar si el ID del cliente se ha pasado como parámetro
if (!isset($_GET['id'])) {
    die("Error: No se ha especificado un cliente para eliminar.");
}

$cliente_id = $_GET['id'];

try {
    // Verificar que el cliente exista
    $stmt = $conn->prepare("SELECT * FROM clientes WHERE id = ?");
    $stmt->execute([$cliente_id]);
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cliente) {
        die("Error: El cliente no existe.");
    }

    // Eliminar el cliente
    $stmt = $conn->prepare("DELETE FROM clientes WHERE id = ?");
    $stmt->execute([$cliente_id]);

    // Redirigir a la página de clientes con un mensaje de éxito
    header("Location: clientes.php?mensaje=Cliente eliminado exitosamente.");
    exit();
} catch (PDOException $e) {
    die("Error al eliminar el cliente: " . $e->getMessage());
}
?>
