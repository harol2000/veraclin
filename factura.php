<?php
require('fpdf/fpdf.php');
require('phpqrcode/qrlib.php');
include 'config.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Obtener el ID de la venta
$venta_id = $_GET['venta_id'] ?? null;
if (!$venta_id) {
    die("Venta no especificada.");
}

// Obtener los datos de la empresa
$stmt = $conn->query("SELECT * FROM datos_empresa LIMIT 1");
$empresa = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$empresa) {
    die("Error: No se encontraron los datos de la empresa.");
}

// Obtener los datos de la venta
$stmt = $conn->prepare("
    SELECT v.id AS venta_id, v.total, v.fecha, c.nombre AS cliente, c.correo AS correo, u.nombre AS vendedor
    FROM ventas v
    JOIN clientes c ON v.cliente_id = c.id
    JOIN usuarios u ON v.user_id = u.id
    WHERE v.id = ?
");
$stmt->execute([$venta_id]);
$venta = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$venta) {
    die("Venta no encontrada.");
}

// Obtener el detalle de los productos vendidos
$stmt = $conn->prepare("
    SELECT p.nombre AS producto, s.cantidad, s.subtotal, p.precio_venta AS precio_unitario
    FROM salidas s
    JOIN productos p ON s.producto_id = p.id
    WHERE s.venta_id = ?
");
$stmt->execute([$venta_id]);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Crear el PDF
$pdf = new FPDF('P', 'mm', [100, 200]);
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);

// Encabezado de la tienda
$pdf->Cell(0, 10, $empresa['nombre_empresa'], 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 5, "Razon Social: " . $empresa['razon_social'], 0, 1, 'C');
$pdf->Cell(0, 5, "RUC: " . $empresa['ruc'], 0, 1, 'C');
$pdf->Cell(0, 5, "Direccion: " . $empresa['direccion'], 0, 1, 'C');
$pdf->Cell(0, 5, "Telefono: " . $empresa['telefono'], 0, 1, 'C');
$pdf->Ln(5);

// Información del cliente y venta
$pdf->SetFont('Arial', '', 9);
$pdf->SetX(5);
$pdf->Cell(0, 5, "Cliente: " . $venta['cliente'], 0, 1, 'L');
$pdf->SetX(5);
$pdf->Cell(0, 5, "Correo: " . $venta['correo'], 0, 1, 'L');
$pdf->SetX(5);
$pdf->Cell(0, 5, "Vendedor: " . $venta['vendedor'], 0, 1, 'L');
$pdf->SetX(5);
$pdf->Cell(0, 5, "Fecha: " . $venta['fecha'], 0, 1, 'L');
$pdf->SetX(5);
$pdf->Cell(0, 5, "Venta ID: " . $venta['venta_id'], 0, 1, 'L');
$pdf->Ln(5);

// Detalle de los productos
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetX(5);
$pdf->Cell(40, 5, "Producto", 0, 0, 'L'); // Columna de producto
$pdf->Cell(15, 5, "Cnt.", 0, 0, 'C'); // Columna de cantidad
$pdf->Cell(20, 5, "P.Unit", 0, 0, 'C'); // Columna de precio unitario
$pdf->Cell(20, 5, "Total", 0, 1, 'R'); // Columna de subtotal

$pdf->SetFont('Arial', '', 8);

$base_imponible = 0;
$igv_total = 0;

foreach ($productos as $producto) {
    $precio_sin_igv = $producto['precio_unitario'] / 1.18;
    $subtotal_producto = $precio_sin_igv * $producto['cantidad'];
    $base_imponible += $subtotal_producto;

    $pdf->SetX(5);
    $pdf->Cell(40, 5, $producto['producto'], 0, 0, 'L'); // Producto
    $pdf->Cell(15, 5, $producto['cantidad'], 0, 0, 'C'); // Cantidad
    $pdf->Cell(20, 5, "S/ " . number_format($precio_sin_igv, 2), 0, 0, 'C'); // Precio Unitario
    $pdf->Cell(20, 5, "S/ " . number_format($subtotal_producto, 2), 0, 1, 'R'); // Subtotal Producto
}

// Cálculo del IGV
$igv_total = $base_imponible * 0.18;
$total = $base_imponible + $igv_total;

// Mostrar Totales
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetX(5);
$pdf->Cell(50, 5, "Base Imponible", 0, 0, 'L');
$pdf->Cell(40, 5, "S/ " . number_format($base_imponible, 2), 0, 1, 'R');

$pdf->SetX(5);
$pdf->Cell(50, 5, "IGV (18%)", 0, 0, 'L');
$pdf->Cell(40, 5, "S/ " . number_format($igv_total, 2), 0, 1, 'R');

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetX(5);
$pdf->Cell(50, 5, "Total", 0, 0, 'L');
$pdf->Cell(40, 5, "S/ " . number_format($total, 2), 0, 1, 'R');

// Generar QR con la información actualizada
$qr_data = "Venta ID: " . $venta['venta_id'] . "\n" .
           "Cliente: " . $venta['cliente'] . "\n" .
           "Fecha: " . $venta['fecha'] . "\n" .
           "Base Imponible: S/" . number_format($base_imponible, 2) . "\n" .
           "IGV: S/" . number_format($igv_total, 2) . "\n" .
           "Total: S/" . number_format($total, 2);

$qr_temp_file = 'temp_qr_' . $venta_id . '.png';
QRcode::png($qr_data, $qr_temp_file, QR_ECLEVEL_L, 4);

$pdf->Cell(0, 5, "QR:", 0, 1);
$pdf->Image($qr_temp_file, 35, $pdf->GetY(), 30, 30);
$pdf->Ln(35);

$pdf->SetFont('Arial', 'I', 9);
$pdf->Cell(0, 5, "Gracias por su compra!", 0, 1, 'C');
$pdf->Cell(0, 5, "Visitenos nuevamente.", 0, 1, 'C');

$pdf->Output('I', 'Factura_' . $venta_id . '.pdf');
?>
