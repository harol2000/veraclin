<?php
include 'config.php';
session_start();

// Verificar si el usuario es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'administrador') {
    header("Location: login.php");
    exit();
}

// Obtener el ID del vendedor
$vendedor_id = $_GET['vendedor_id'] ?? null;

if (!$vendedor_id) {
    die("Error: ID del vendedor no especificado.");
}

// Verificar si el vendedor existe
$stmt = $conn->prepare("SELECT nombre FROM usuarios WHERE id = ? AND rol = 'vendedor'");
$stmt->execute([$vendedor_id]);
$vendedor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$vendedor) {
    die("Error: Vendedor no encontrado.");
}

// Obtener las ventas realizadas por el vendedor
$query = $conn->prepare("
    SELECT 
        v.id AS venta_id, 
        c.nombre AS cliente, 
        v.total, 
        v.fecha, 
        GROUP_CONCAT(CONCAT(p.nombre, ' (', sv.cantidad, ' x S/ ', FORMAT(p.precio_venta, 2), ')') SEPARATOR '<br>') AS productos
    FROM ventas v
    JOIN clientes c ON v.cliente_id = c.id
    JOIN salidas sv ON v.id = sv.venta_id
    JOIN productos p ON sv.producto_id = p.id
    WHERE v.user_id = ?
    GROUP BY v.id
    ORDER BY v.fecha DESC
");
$query->execute([$vendedor_id]);
$ventas = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ventas de <?= htmlspecialchars($vendedor['nombre']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center">Reporte de Ventas</h1>
        <h3 class="text-center">Vendedor: <?= htmlspecialchars($vendedor['nombre']) ?></h3>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID Venta</th>
                    <th>Cliente</th>
                    <th>Productos</th>
                    <th>Total</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($ventas) > 0): ?>
                    <?php foreach ($ventas as $venta): ?>
                        <tr>
                            <td><?= htmlspecialchars($venta['venta_id']) ?></td>
                            <td><?= htmlspecialchars($venta['cliente']) ?></td>
                            <td><?= $venta['productos'] ?></td>
                            <td>S/ <?= number_format($venta['total'], 2) ?></td>
                            <td><?= htmlspecialchars($venta['fecha']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No se encontraron ventas realizadas por este vendedor.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
