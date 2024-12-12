<?php
include 'config.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Verificar si se pasa el número de documento
if (!isset($_GET['doc'])) {
    die("Error: Número de documento no especificado.");
}

$numero_documento = $_GET['doc'];

// Consulta para obtener los detalles de la entrada
$stmt = $conn->prepare("
    SELECT 
        k.numero_documento,
        k.fecha,
        k.ingresos AS cantidad,
        p.nombre AS producto,
        p.precio_compra AS precio_unitario,
        (p.precio_compra * k.ingresos) AS precio_total,
        pr.nombre AS proveedor,
        pr.ruc,
        pr.direccion,
        pr.telefono,
        pr.departamento,
        pr.provincia,
        pr.distrito
    FROM kardex k
    JOIN productos p ON k.producto_id = p.id
    JOIN proveedores pr ON pr.id = (
        SELECT proveedor_id 
        FROM entradas 
        WHERE producto_id = k.producto_id 
        LIMIT 1
    )
    WHERE k.numero_documento = ?
    AND k.tipo_documento = 'entrada'
");
$stmt->execute([$numero_documento]);
$entrada = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar si se encontró la entrada
if (!$entrada) {
    die("Error: No se encontró una entrada para el número de documento especificado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Boleta de Compra</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .ticket {
            width: 300px;
            border: 1px solid #ddd;
            padding: 10px;
            margin: 0 auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .ticket-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .ticket-details {
            margin-bottom: 20px;
        }
        .ticket-details p {
            margin: 5px 0;
        }
        .ticket-footer {
            text-align: center;
            font-size: 12px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="ticket-header">
            <h2>Boleta de Compra</h2>
            <p>Número: <?= htmlspecialchars($entrada['numero_documento']) ?></p>
            <p>Fecha: <?= htmlspecialchars($entrada['fecha']) ?></p>
        </div>
        <div class="ticket-details">
            <p><strong>Proveedor:</strong> <?= htmlspecialchars($entrada['proveedor']) ?></p>
            <p><strong>RUC:</strong> <?= htmlspecialchars($entrada['ruc']) ?></p>
            <p><strong>Dirección:</strong> <?= htmlspecialchars($entrada['direccion']) ?></p>
            <p><strong>Teléfono:</strong> <?= htmlspecialchars($entrada['telefono']) ?></p>
            <p><strong>Ubicación:</strong> 
                <?= htmlspecialchars($entrada['departamento']) ?>, 
                <?= htmlspecialchars($entrada['provincia']) ?>, 
                <?= htmlspecialchars($entrada['distrito']) ?>
            </p>
            <p><strong>Producto:</strong> <?= htmlspecialchars($entrada['producto']) ?></p>
            <p><strong>Cantidad:</strong> <?= htmlspecialchars($entrada['cantidad']) ?></p>
            <p><strong>Precio Unitario:</strong> S/ <?= number_format($entrada['precio_unitario'], 2) ?></p>
            <p><strong>Precio Total:</strong> S/ <?= number_format($entrada['precio_total'], 2) ?></p>
        </div>
        <div class="ticket-footer">
            <p>Gracias por su compra.</p>
        </div>
    </div>
</body>
</html>
