<?php
include 'config.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Verificar si se ha proporcionado un ID de proveedor
$proveedor_id = $_GET['proveedor_id'] ?? null;
if (!$proveedor_id) {
    die("Error: Proveedor no especificado.");
}

// Obtener los datos del proveedor
$stmt = $conn->prepare("SELECT * FROM proveedores WHERE id = ?");
$stmt->execute([$proveedor_id]);
$proveedor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$proveedor) {
    die("Error: Proveedor no encontrado.");
}

// Obtener las entradas correspondientes al proveedor
$stmt = $conn->prepare("
    SELECT 
        e.id AS entrada_id, 
        p.nombre AS producto, 
        e.cantidad, 
        e.fecha, 
        p.precio_compra 
    FROM entradas e
    JOIN productos p ON e.producto_id = p.id
    WHERE e.proveedor_id = ?
    ORDER BY e.fecha DESC
");
$stmt->execute([$proveedor_id]);
$entradas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Entradas - <?= htmlspecialchars($proveedor['nombre']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container my-5">
        <h1 class="text-center">Reporte de Entradas</h1>
        <h4 class="text-center">Proveedor: <?= htmlspecialchars($proveedor['nombre']) ?></h4>
        <h5 class="text-center">RUC: <?= htmlspecialchars($proveedor['ruc']) ?></h5>
        <div class="mt-4">
            <?php if (count($entradas) > 0): ?>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID Entrada</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Total</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($entradas as $entrada): ?>
                        <tr>
                            <td><?= htmlspecialchars($entrada['entrada_id']) ?></td>
                            <td><?= htmlspecialchars($entrada['producto']) ?></td>
                            <td><?= htmlspecialchars($entrada['cantidad']) ?></td>
                            <td>S/ <?= number_format($entrada['precio_compra'], 2) ?></td>
                            <td>S/ <?= number_format($entrada['cantidad'] * $entrada['precio_compra'], 2) ?></td>
                            <td><?= htmlspecialchars($entrada['fecha']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center">No hay entradas registradas para este proveedor.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
