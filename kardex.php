<?php
include 'config.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Validar si el producto_id ha sido pasado como parámetro
if (!isset($_GET['producto_id'])) {
    echo "No se ha especificado un producto.";
    exit();
}

$producto_id = $_GET['producto_id'];

// Verificar que el producto exista
$stmt = $conn->prepare("SELECT nombre FROM productos WHERE id = ?");
$stmt->execute([$producto_id]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    echo "El producto no existe.";
    exit();
}

// Obtener los movimientos del Kardex para el producto
$stmt = $conn->prepare("
    SELECT k.*, p.nombre AS producto 
    FROM kardex k
    JOIN productos p ON k.producto_id = p.id
    WHERE k.producto_id = ?
    ORDER BY k.fecha ASC
");
$stmt->execute([$producto_id]);
$movimientos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Kardex de <?= htmlspecialchars($producto['nombre']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container my-5">
        <h1 class="text-center">Kardex de <?= htmlspecialchars($producto['nombre']) ?></h1>
        <?php if (count($movimientos) > 0): ?>
            <table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>Fecha</th>
            <th>Tipo de Documento</th>
            <th>Número de Documento</th>
            <th>Ingresos</th>
            <th>Salidas</th>
            <th>Saldo</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($movimientos as $movimiento): ?>
    <tr>
        <td><?= htmlspecialchars($movimiento['fecha']) ?></td>
        <td>
            <?php if ($movimiento['tipo_documento'] === 'entrada'): ?>
                <!-- Botón para ver el PDF de compra -->
                <a href="boleta_compra.php?doc=<?= htmlspecialchars($movimiento['numero_documento']) ?>" 
                   class="btn btn-success btn-sm">
                   <?= htmlspecialchars($movimiento['tipo_documento']) ?>
                </a>
            <?php else: ?>
                <!-- Mostrar el tipo de documento como texto -->
                <?= htmlspecialchars($movimiento['tipo_documento']) ?>
            <?php endif; ?>
        </td>
        <td><?= htmlspecialchars($movimiento['numero_documento']) ?></td>
        <td><?= $movimiento['ingresos'] > 0 ? htmlspecialchars($movimiento['ingresos']) : '-' ?></td>
        <td><?= $movimiento['salidas'] > 0 ? htmlspecialchars($movimiento['salidas']) : '-' ?></td>
        <td><?= htmlspecialchars($movimiento['saldo']) ?></td>
    </tr>
    <?php endforeach; ?>
</tbody>

</table>

        <?php else: ?>
            <p class="text-center">No hay movimientos registrados para este producto.</p>
        <?php endif; ?>
    </div>
</body>
</html>
