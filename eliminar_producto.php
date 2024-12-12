<?php
include 'config.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Verificar si el ID del producto ha sido pasado como parámetro
if (!isset($_GET['id'])) {
    header("Location: productos.php");
    exit();
}

$id = $_GET['id'];

// Verificar si el producto existe antes de eliminarlo
$stmt = $conn->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->execute([$id]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    echo "El producto no existe.";
    exit();
}

// Eliminar el producto
$stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
$stmt->execute([$id]);

header("Location: productos.php");
exit();
?>
