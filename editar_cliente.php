<?php
include 'config.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Verificar si se ha proporcionado un ID de cliente
$cliente_id = $_GET['id'] ?? null;
if (!$cliente_id) {
    die("Error: ID de cliente no especificado.");
}

// Obtener los datos del cliente
$stmt = $conn->prepare("SELECT * FROM clientes WHERE id = ?");
$stmt->execute([$cliente_id]);
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cliente) {
    die("Error: Cliente no encontrado.");
}

// Manejar la actualización del cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $ruc = $_POST['ruc'];

    // Validar campos obligatorios
    if (empty($nombre) || empty($correo) || empty($telefono) || empty($direccion)) {
        die("Error: Todos los campos son obligatorios.");
    }

    // Actualizar los datos del cliente en la base de datos
    $stmt = $conn->prepare("UPDATE clientes SET nombre = ?, correo = ?, telefono = ?, direccion = ?, ruc = ? WHERE id = ?");
    $stmt->execute([$nombre, $correo, $telefono, $direccion, $ruc, $cliente_id]);

    // Redirigir a la lista de clientes
    header("Location: clientes.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container my-5">
        <h1 class="text-center">Editar Cliente</h1>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" name="nombre" id="nombre" class="form-control" value="<?= htmlspecialchars($cliente['nombre']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo:</label>
                <input type="email" name="correo" id="correo" class="form-control" value="<?= htmlspecialchars($cliente['correo']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono:</label>
                <input type="text" name="telefono" id="telefono" class="form-control" value="<?= htmlspecialchars($cliente['telefono']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección:</label>
                <input type="text" name="direccion" id="direccion" class="form-control" value="<?= htmlspecialchars($cliente['direccion']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="ruc" class="form-label">RUC:</label>
                <input type="text" name="ruc" id="ruc" class="form-control" value="<?= htmlspecialchars($cliente['ruc']) ?>" maxlength="11">
            </div>
            <button type="submit" class="btn btn-primary w-100">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>
