<?php
include 'config.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Obtener lista de productos y proveedores
$productos = $conn->query("SELECT * FROM productos")->fetchAll(PDO::FETCH_ASSOC);
$proveedores = $conn->query("SELECT * FROM proveedores")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $producto_id = $_POST['producto_id'];
    $proveedor_id = $_POST['proveedor_id'];
    $cantidad = $_POST['cantidad'];

    // Registrar la entrada
    $stmt = $conn->prepare("INSERT INTO entradas (producto_id, proveedor_id, cantidad) VALUES (?, ?, ?)");
    $stmt->execute([$producto_id, $proveedor_id, $cantidad]);

    // Actualizar el stock del producto
    $stmt = $conn->prepare("UPDATE productos SET stock = stock + ? WHERE id = ?");
    $stmt->execute([$cantidad, $producto_id]);

    // Registrar movimiento en el Kardex
    $stmt = $conn->prepare("INSERT INTO kardex (producto_id, fecha, tipo_documento, numero_documento, estado, ingresos, saldo) VALUES (?, NOW(), 'entrada', ?, 'emitido', ?, (SELECT stock FROM productos WHERE id = ?))");
    $numero_documento = "ENT-" . time(); // Generar un número de documento único
    $stmt->execute([$producto_id, $numero_documento, $cantidad, $producto_id]);

    header("Location: entradas.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Entrada</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container my-5">
        <h1 class="text-center">Registrar Entrada</h1>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="producto_id" class="form-label">Producto:</label>
                <select name="producto_id" id="producto_id" class="form-select" required>
                    <option value="">Seleccione un producto</option>
                    <?php foreach ($productos as $producto): ?>
                        <option value="<?= $producto['id'] ?>"><?= $producto['nombre'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="proveedor_id" class="form-label">Proveedor:</label>
                <select name="proveedor_id" id="proveedor_id" class="form-select" required>
                    <option value="">Seleccione un proveedor</option>
                    <?php foreach ($proveedores as $proveedor): ?>
                        <option value="<?= $proveedor['id'] ?>"><?= $proveedor['nombre'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="cantidad" class="form-label">Cantidad:</label>
                <input type="number" name="cantidad" id="cantidad" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Registrar Entrada</button>
        </form>
    </div>
</body>
</html>
