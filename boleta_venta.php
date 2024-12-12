<?php
if (!isset($_GET['doc'])) {
    die("No se especificó un documento.");
}

$numero_documento = htmlspecialchars($_GET['doc']); // Número del documento

// Aquí carga los datos de la venta de la base de datos
include 'config.php';
$stmt = $conn->prepare("SELECT * FROM ventas WHERE numero_documento = ?");
$stmt->execute([$numero_documento]);
$venta = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$venta) {
    die("Documento no encontrado.");
}

// Genera la boleta/ticket
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Boleta de Venta <?= $numero_documento ?></title>
</head>
<body>
    <h1>Boleta de Venta</h1>
    <p>Documento: <?= $numero_documento ?></p>
    <p>Cliente: <?= $venta['cliente'] ?></p>
    <p>Total: <?= $venta['total'] ?></p>
    <p>Fecha: <?= $venta['fecha'] ?></p>
    <!-- Aquí añade más detalles según necesites -->
</body>
</html>
