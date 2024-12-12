<?php
include 'config.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Consulta SQL para obtener las ventas con los detalles, incluyendo el RUC del cliente
$query = $conn->query("
    SELECT 
        v.id AS venta_id, 
        c.nombre AS cliente, 
        c.ruc AS cliente_ruc, 
        v.total, 
        v.fecha, 
        u.nombre AS vendedor,
        IFNULL(GROUP_CONCAT(CONCAT(p.nombre, ' (', sv.cantidad, ' x S/ ', FORMAT(p.precio_venta, 2), ')') SEPARATOR '<br>'), 'Sin productos') AS productos
    FROM ventas v
    JOIN clientes c ON v.cliente_id = c.id
    JOIN usuarios u ON v.user_id = u.id
    LEFT JOIN salidas sv ON v.id = sv.venta_id
    LEFT JOIN productos p ON sv.producto_id = p.id
    GROUP BY v.id
    ORDER BY v.fecha DESC
");

$ventas = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ventas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?> <!-- Navbar -->
    <div class="container my-5">
        <h1 class="text-center">Reporte de Ventas</h1>
        <div class="text-end mb-3">
            <a href="registrar_venta.php" class="btn btn-success">Registrar Nueva Venta</a>
        </div>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID Venta</th>
                    <th>Cliente</th>
                    <th>RUC</th>
                    <th>Productos (Cantidad x Precio)</th>
                    <th>Total</th>
                    <th>Fecha</th>
                    <th>Vendedor</th> <!-- Nueva columna -->
                    <th>Acciones</th> <!-- Columna para el botón del ticket -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ventas as $venta): ?>
                <tr>
                    <td><?= htmlspecialchars($venta['venta_id']) ?></td>
                    <td><?= htmlspecialchars($venta['cliente']) ?></td>
                    <td><?= htmlspecialchars($venta['cliente_ruc']) ?></td>
                    <td><?= $venta['productos'] ?></td>
                    <td>S/ <?= number_format($venta['total'], 2) ?></td>
                    <td><?= htmlspecialchars($venta['fecha']) ?></td>
                    <td><?= htmlspecialchars($venta['vendedor']) ?></td> <!-- Mostrar vendedor -->
                    <td>
                        <a href="factura.php?venta_id=<?= $venta['venta_id'] ?>" 
                           class="btn btn-primary btn-sm" 
                           target="_blank">Ver Ticket</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

