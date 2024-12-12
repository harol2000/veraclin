<?php
include 'config.php';
session_start();

// Verificar si el usuario es administrador
if ($_SESSION['user_role'] !== 'administrador') {
    header("Location: dashboard.php");
    exit();
}

// Obtener datos actuales
$stmt = $conn->query("SELECT * FROM datos_empresa LIMIT 1");
$empresa = $stmt->fetch(PDO::FETCH_ASSOC);

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ruc = trim($_POST['ruc']);
    $nombre_empresa = trim($_POST['nombre_empresa']);
    $razon_social = trim($_POST['razon_social']);
    $telefono = trim($_POST['telefono']);
    $correo = trim($_POST['correo']);
    $direccion = trim($_POST['direccion']);
    $igv = floatval($_POST['igv']);

    // Validaciones
    if (!preg_match("/^\d{11}$/", $ruc)) {
        die("Error: El RUC debe tener 11 dígitos.");
    }
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        die("Error: Correo inválido.");
    }
    if ($igv <= 0 || $igv > 100) {
        die("Error: El IGV debe ser un porcentaje válido.");
    }

    // Actualizar datos
    $stmt = $conn->prepare("
        UPDATE datos_empresa 
        SET ruc = ?, nombre_empresa = ?, razon_social = ?, telefono = ?, correo = ?, direccion = ?, igv = ?
    ");
    $stmt->execute([$ruc, $nombre_empresa, $razon_social, $telefono, $correo, $direccion, $igv]);

    header("Location: dashboard.php?mensaje=Datos de la empresa actualizados.");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Datos de la Empresa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container my-5">
        <h1 class="text-center">Editar Datos de la Empresa</h1>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="ruc" class="form-label">RUC</label>
                <input type="text" name="ruc" id="ruc" class="form-control" value="<?= htmlspecialchars($empresa['ruc']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="nombre_empresa" class="form-label">Nombre de la Empresa</label>
                <input type="text" name="nombre_empresa" id="nombre_empresa" class="form-control" value="<?= htmlspecialchars($empresa['nombre_empresa']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="razon_social" class="form-label">Razón Social</label>
                <input type="text" name="razon_social" id="razon_social" class="form-control" value="<?= htmlspecialchars($empresa['razon_social']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" name="telefono" id="telefono" class="form-control" value="<?= htmlspecialchars($empresa['telefono']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo</label>
                <input type="email" name="correo" id="correo" class="form-control" value="<?= htmlspecialchars($empresa['correo']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" name="direccion" id="direccion" class="form-control" value="<?= htmlspecialchars($empresa['direccion']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="igv" class="form-label">IGV (%)</label>
                <input type="number" name="igv" id="igv" class="form-control" value="<?= htmlspecialchars($empresa['igv']) ?>" step="0.01" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>
