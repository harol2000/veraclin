<?php
require('phpqrcode/qrlib.php'); // Asegúrate de que la ruta sea correcta

// Datos para generar el QR
$data = "Prueba de generación de QR con PHP";

// Ruta del archivo temporal
$tempFile = 'test_qr.png';

// Generar el QR
QRcode::png($data, $tempFile, QR_ECLEVEL_L, 4);

// Verificar si el archivo fue creado
if (file_exists($tempFile)) {
    echo "QR generado correctamente. Puedes verlo aquí: <br>";
    echo "<img src='$tempFile' alt='QR Code'>";
} else {
    echo "No se pudo generar el QR.";
}
?>
