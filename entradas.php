<?php
include 'config.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Obtener datos de entradas
$stmt = $conn->query("
SELECT 
        e.id, 
        p.nombre AS producto, 
        pr.nombre AS proveedor, 
        e.cantidad, 
        e.precio_entrada, 
        e.fecha 
    FROM entradas e
    JOIN productos p ON e.producto_id = p.id
    JOIN proveedores pr ON e.proveedor_id = pr.id
    ORDER BY e.fecha DESC
");
$entradas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Entradas</title>



    <style>
/* navbar {
    z-index: 1030;
    position: relative;
} */
</style>


</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container my-5">
        <h1 class="text-center">Reporte de Entradas</h1>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Producto</th>
                    <th>Proveedor</th>
                    <th>Cantidad</th>
                    <th>Precio de Entrada</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($entradas as $entrada): ?>
                <tr>
                    <td><?= $entrada['id'] ?></td>
                    <td><?= $entrada['producto'] ?></td>
                    <td><?= $entrada['proveedor'] ?></td>
                    <td><?= $entrada['cantidad'] ?></td>
                    <td>S/ <?= number_format($entrada['precio_entrada'], 2) ?></td>
                    <td><?= $entrada['fecha'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
