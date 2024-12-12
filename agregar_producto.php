<?php
include 'config.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Obtener la lista de proveedores
$proveedores = $conn->query("SELECT * FROM proveedores")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio_compra = $_POST['precio_compra'];
    $precio_venta = $_POST['precio_venta'];
    $stock = $_POST['stock'];
    $proveedor_id = $_POST['proveedor_id'];

    // Manejo de la imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $nombreArchivo = $_FILES['imagen']['name'];
        $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
        $nombreUnico = uniqid('prod_') . '.' . $extension;
        $rutaDestino = 'uploads/' . $nombreUnico;

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
            $imagen = $nombreUnico;
        } else {
            die("Error al subir la imagen.");
        }
    } else {
        die("Error: Debes subir una imagen válida.");
    }

    // Insertar el producto en la tabla `productos`
    $stmt = $conn->prepare("
        INSERT INTO productos (nombre, descripcion, precio_compra, precio_venta, stock, imagen) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$nombre, $descripcion, $precio_compra, $precio_venta, $stock, $imagen]);

    // Obtener el ID del producto recién insertado
    $producto_id = $conn->lastInsertId();

    // Registrar entrada en la tabla `entradas`
    $stmt = $conn->prepare("
        INSERT INTO entradas (producto_id, proveedor_id, cantidad, precio_entrada, fecha) 
        VALUES (?, ?, ?, ?, NOW())
    ");
    $stmt->execute([$producto_id, $proveedor_id, $stock, $precio_compra]);


    // Registrar en el Kardex
    $stmt = $conn->prepare("
        INSERT INTO kardex (producto_id, fecha, tipo_documento, numero_documento, ingresos, salidas, saldo) 
        VALUES (?, NOW(), 'entrada', ?, ?, 0, ?)
    ");
    $numero_documento = "REGISTRO-" . $producto_id;
    $stmt->execute([$producto_id, $numero_documento, $stock, $stock]);

    // Redirigir al listado de productos
    header("Location: productos.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container my-5">
        <h1 class="text-center">Agregar Producto</h1>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Producto:</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción:</label>
                <textarea name="descripcion" id="descripcion" class="form-control" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="precio_compra" class="form-label">Precio de Compra:</label>
                <input type="number" step="0.01" name="precio_compra" id="precio_compra" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="precio_venta" class="form-label">Precio de Venta:</label>
                <input type="number" step="0.01" name="precio_venta" id="precio_venta" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="stock" class="form-label">Stock Inicial:</label>
                <input type="number" name="stock" id="stock" class="form-control" required>
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
                <label for="imagen" class="form-label">Imagen del Producto:</label>
                <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Agregar Producto</button>
        </form>
    </div>
</body>
</html>
